<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Implement ROLE BASED ACCESS
//TODO: Test API
//TODO: Register to api.php
//TODO: Add documentation
class CurriculumController extends Controller
{
    public function index(){

        return response()->json([
            ['status' => "success"],
            Curriculum::all()
                ->with(['curriculum_subjects', function(Builder $query){
                    $query
                        ->with('subjects')
                        ->with('semesters')
                        ->get();
                }])
            ->with('curriculum_subjects')
        ]);
    }

    public function show(Request $request, String $id){
        $curriculum = Curriculum::where('cid', $id)
            ->with(['curriculum_subjects', function(Builder $query) {
                $query
                    ->with('subjects')
                    ->with('semesters')
                    ->get();
            }])
            ->first();

        if ($curriculum != null) {
           return response()->json([
                ['status' => 'success'],
                $curriculum
           ], 200);
        }

        return response()->json(['status' => 'not found'], 404);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'programid' => ['required', 'integer'],
            'subjects' => ['required', 'array', function($attribute, $value, $validator) {
                foreach ($value as $key => $subject) {
                    $validator->addRule("{$attribute}.{$key}.subjectid", 'required|integer');
                    $validator->addRule("{$attribute}.{$key}.semid", 'required|integer');
                }
            }],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $curriculum = Curriculum::create([
            'programid' => $request->programid
        ]);

        foreach($request->subjects as $subject) {
            $curriculum->curriculum_subjects()->attach($subject);
        }

        return response()->json([
            ['status' => 'success'],
            $curriculum
        ], 200);
    }

    public function delete(Request $request, String $id){
        $curriculum = Curriculum::where('cid', $id)->first();

        if ($curriculum != null){
            $curriculum->delete();
            return response()->json(['status' => 'successfully deleted'], 200);
        }
        return response()->json(['status' => 'not found'], 404);
    }

    public function update(Request $request, String $id)
    {
        $validate = Validator::make($request->all(), [
            'programid' => ['required', 'integer'],
            'subjects' => ['nullable', 'array', function($attribute, $value, $validator) {
                if (!$value) {
                    return;
                }

                foreach ($value as $key => $subject) {
                    $validator->addRule("{$attribute}.{$key}.subjectid", 'required|integer');
                    $validator->addRule("{$attribute}.{$key}.semid", 'required|integer');
                    $validator->addRule("{$attribute}.{$key}.action", 'required|string|in:update,delete');
                }
            }],
        ]);

        if ($validate->fails()) {
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $curriculum = Curriculum::where('cid', $id)->first();

        if ($curriculum === null) {
            return response()->json(['status' => 'not found'], 404);
        }

        // Update program ID
        $curriculum->programid = $request->programid;
        $curriculum->save();

        if (!empty($request->subjects)) {
            $subjectIdsToUpdate = [];
            $subjectIdsToDelete = [];

            foreach ($request->subjects as $subject) {
                $action = $subject['action'];
                $subjectId = $subject['subjectid'];
                $semesterId = $subject['semid'];

                switch ($action) {
                    case 'update':
                        $curriculum->curriculum_subjects()->updateExistingPivot($subjectId, ['semid' => $semesterId]);
                        break;
                    case 'delete':
                        $curriculum->curriculum_subjects()->detach($subjectId);
                        break;
                    default:
                        return response()->json(['status' => 'invalid_action'], 400);
                }
            }
        }

        return response()->json([
            ['status' => 'success'],
            $curriculum,
        ], 200);
    }
}
