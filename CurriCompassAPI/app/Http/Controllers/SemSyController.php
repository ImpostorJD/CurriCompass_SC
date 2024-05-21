<?php

namespace App\Http\Controllers;

use App\Models\SemSy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SemSyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            SemSy::with('semester')
                ->with('school_year')
                ->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'sy' => ['required', 'integer'],
            'semid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        if(SemSy::where('sy', $request['sy'])->where('semid', $request['semid'])->first()){
            return response()->json([['status' => 'duplicate'], $validate->errors()] ,409);
        }

        return response()->json([
            ['status' => 'resource created successfully'],
            SemSy::create([
                'sy' => $request['sy'],
                'semid' => $request['semid'],
            ])
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = SemSy::where('semsyid', $id)->first();
        if ($record){
            return response()->json([
                ['status' => 'success'],
                $record,
            ], 200);
        }
        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validate = Validator::make($request->all(), [
            'sy' => ['required', 'integer'],
            'semid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $record = SemSy::where('semsyid', $id)->first();
        if ($record){
            $existing_record = SemSy::where('sy', $request['sy'])->where('semid', $request['semid'])->first();
            if($existing_record && $existing_record->semsyid != $id){
                return response()->json([['status' => 'duplicate'], $validate->errors()] ,409);
            }
            return response()->json([
                ['status' => 'success'],
                $record->update([
                    'sy' => $request['sy'],
                    'semid' => $request['semid'],
                ]),
            ], 200);
        }
        return response()->json([
            ['status' => 'not found'],
        ], 404);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currentRecord = SemSy::where('semsyid', $id)
            ->with('course_availability')
            ->first();

        if($currentRecord) {
            $deletable = true;
            $messages = [];
            if ($currentRecord->course_availability()->count() > 0) {
                $messages['course_availability'] = "Course Availability currently have student records.";
                $deletable = false;
            }

            if(!$deletable) return response()->json([
                ['status', 'bad request'],
                $messages
            ], 400);

            return response()->json([
                ['status'=> "deleted successfully."],
                $currentRecord->delete(),
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }
}
