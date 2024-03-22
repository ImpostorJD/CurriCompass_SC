<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolYearController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index(Request $request)
    {
        return response()->json([
            ["status" => "success"],
            SchoolYear::all(),
        ], 200);
    }
    public function show(Request $request, String $id)
    {

        $sy = SchoolYear::where('sy', $id)->first();
        if ($sy){
            return response()->json([
                ['status' => 'success'],
                $sy
            ]);
        }

        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sy_start' => ['required', 'date_format:Y/m/d'],
            'sy_end' => ['required', 'date_format:Y/m/d'],
        ]);

        if($validator->fails()){
            return response()->json([
                ['status' => 'bad request'],
                $validator->errors(),
            ], 400);
        }

        $existing = SchoolYear::where('sy_end', $request->sy_end)
            ->where('sy_start', $request->sy_start)
            ->first();

        if($existing){
            return response()->json([
                ['status' => 'Conflict'],
                ["message" => "Duplicate in combination of year start and year end."]
            ], 409);
        }
        return response()->json([
            ["staus" => "success"],
            SchoolYear::create([
                'sy_start' => $request->sy_start,
                'sy_end' => $request->sy_end,
            ], 200),
        ]);
    }

    public function update(Request $request, String $id)
    {
        $validator = Validator::make($request->all(), [
            'sy_start' => ['required', 'date_format:Y/m/d'],
            'sy_end' => ['required', 'date_format:Y/m/d'],
        ]);

        if($validator->fails()){
            return response()->json([
                ['status' => 'bad request'],
                $validator->errors(),
            ], 400);
        }

        $currentRecord = SchoolYear::where('sy', $id)->first();

        if($currentRecord){
            $existing = SchoolYear::where('sy_end', $request->sy_end)
                ->where('sy_start', $request->sy_start)
                ->first();

            if($existing &&
            ($currentRecord->sy_start != $existing->sy_start &&
            $currentRecord->sy_end != $existing->sy_end)){
                return response()->json([
                    ['status' => 'Conflict'],
                    ["message" => "Duplicate in combination of year start and year end."]
                ], 409);
            }

            return response()->json([
                ["staus" => "success"],
                $currentRecord->update([
                    'sy_start' => $request->sy_start,
                    'sy_end' => $request->sy_end,
                ]),
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }

    public function destroy(Request $request, String $id)
    {
        $currentRecord = SchoolYear::where('sy', $id)
            ->with('student_records')
            ->with('curriculum')
            ->with('subjects_taken')
            ->first();

        if($currentRecord) {
            $deletable = true;
            $messages = [];
            if ($currentRecord->student_records()->count() > 0) {
                $messages['student_records'] = "School year currently have student records.";
                $deletable = false;
            }

            if ($currentRecord->curriculum()->count() > 0) {
                $messages['curriculum'] = "School year currently have curricula.";
                $deletable = false;
            }

            if ($currentRecord->subjects_taken()->count() > 0) {
                $messages['subjects_taken'] = "School year currently have subjects taken.";
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
