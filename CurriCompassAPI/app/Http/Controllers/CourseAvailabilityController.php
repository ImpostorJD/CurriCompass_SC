<?php

namespace App\Http\Controllers;

use App\Models\CourseAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseAvailabilityController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            CourseAvailability::with(['semester_sy'=> function($query){
                $query->with('school_year');
                $query->with('semester');
            }])->with('subjects')
                ->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'subjectid' => ['required', 'integer'],
            'semsyid' => ['required', 'integer'],
            'time' => ['required', 'string'],
            'section' => ['required', 'string'],
            'limit' => ['required', 'integer'],
            'days' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $current_record = CourseAvailability::where('subjectid', $request['subjectid'])
            ->where('semsyid', $request['semsyid'])
            ->where('time', $request['time'])
            ->where('section', $request['section'])
            ->where('days', $request['days'])
            ->first();

        if($current_record){
            return response()->json([['status' => 'duplicate'], $validate->errors()] ,409);
        }

        return response()->json([
            ['status' => 'success'],
            CourseAvailability::create([
                'subjectid' => $request['subjectid'],
                'semsyid' => $request['semsyid'],
                'time' => $request['time'],
                'section' => $request['section'],
                'limit' => $request['limit'],
                'days' => $request['days'],
            ]),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $record = CourseAvailability::where('caid', $id)
            ->with(['semester_sy'=> function($query){
                $query->with('school_year');
                $query->with('semester');
            }])
            ->with('subjects')
            ->first();

        if($record){
            return response()->json([
                ['status' => 'success'],
                $record
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
            'subjectid' => ['required', 'integer'],
            'semsyid' => ['required', 'integer'],
            'time' => ['required', 'string'],
            'section' => ['required', 'string'],
            'limit' => ['required', 'integer'],
            'days' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $record = CourseAvailability::where('caid', $id)
            ->with(['semester_sy'=> function($query){
                $query->with('school_year');
                $query->with('semester');
            }])
            ->with('subjects')
            ->first();

        if($record){
            $existingRecord = CourseAvailability::where('subjectid', $request['subjectid'])
                ->where('semsyid', $request['semsyid'])
                ->where('time', $request['time'])
                ->where('section', $request['section'])
                ->first('days', $request['days']);

            if($existingRecord && $existingRecord->caid != $record->caid) return response()
                ->json([
                    ['status' => 'duplicate'],
                ], 409);

            return response()->json([
                ['status' => 'success'],
                $record->update([
                    'subjectid' => $request['subjectid'],
                    'semsyid' => $request['semsyid'],
                    'time' => $request['time'],
                    'section' => $request['section'],
                    'limit' => $request['limit'],
                    'days' => $request['days'],
                ])
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
        $currentRecord = CourseAvailability::where('caid', $id)
        //->with('enlistment')
        ->first();

        if($currentRecord) {

            //TODO: check for enlistment
            $deletable = true;
            $messages = [];
            // if ($currentRecord->course_availability()->count() > 0) {
            //     $messages['course_availability'] = "Course Availability currently have student records.";
            //     $deletable = false;
            // }

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
