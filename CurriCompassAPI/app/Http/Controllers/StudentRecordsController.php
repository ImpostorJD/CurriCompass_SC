<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Implement ROLE BASED ACCESS
//TODO: Test API
//TODO: Add documentation
class StudentRecordsController extends Controller
{
    public function index(){
        return response()->json([
            ['status' => 'success'],
            User::whereHas('user_roles', function(Builder $query){
               $query->where('rolename', '=', 'Student');
            })->with(['student_records', function(Builder $query){
                $query->with(['subjects_taken', function(Builder $query){
                    $query->with('subjects')
                        ->with('semesters')
                        ->with(['curriculum'], function(Builder $query){
                          $query->with('program')->get();
                        })
                        ->get();
                }])->get();
            }])->get()
        ], 200);
    }

    public function show(Request $request, String $id){
        $user = User::where('userid', $id)
            ->whereHas('user_roles', function (Builder $query){
                $query->where('rolename', '=', 'Student');
            })->with(['student_records', function(Builder $query){
                $query->with(['subjects_taken', function(Builder $query){
                    $query->with('subjects')
                        ->with('semesters')
                        ->with(['curriculum'], function(Builder $query){
                            $query->with('program')->get();
                          })
                        ->get();
                }])->get();
            }])->first();

        if($user) {
            return response()->json([
                ['status' => 'success'],
                $user
            ], 200);
        }
        return response()->json([
            'status' => 'not found',
        ], 404);
    }

    public function store(Request $request, String $id){
        $validate = Validator::make($request->all(), [
            'student_no' => ['required', 'string'],
            'year_level' => ['required', 'integer'],
            'status' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $user = User::where('userid', $id)
            ->whereHas('user_roles', function (Builder $query){
                $query->where('rolename', '=', 'Student');
            })->with(['student_records', function(Builder $query){
                $query->with(['subjects_taken', function(Builder $query){
                    $query->with('subjects')
                        ->with('semesters')
                        ->with(['curriculum'], function(Builder $query){
                            $query->with('program')->get();
                          })
                        ->get();
                }])->get();
            }])->first();

        if($user) {
            return response()->json([
                ['status' => 'success'],
                $user->student_record()
                    ->create([
                        'student_no' => $request->student_no,
                        'year_level' => $request->year_level,
                        'status' => $request->status,
                        'userid' => $user->userid,
                    ])
            ], 200);
        }
        return response()->json([
            'status' => 'not found',
        ], 404);
    }

    public function update(Request $request, String $id){
        $validate = Validator::make($request->all(), [
            'student_no' => ['required', 'string'],
            'year_level' => ['required', 'integer'],
            'status' => ['required', 'string'],
            'subjects_taken' => ['nullable', 'array', function($attribute, $value, $validator) {
                if (!$value) {
                    return;
                }

                foreach ($value as $key => $subject) {
                    $validator->addRule("{$attribute}.{$key}.subjectid", 'required|integer');
                    $validator->addRule("{$attribute}.{$key}.taken_at", 'required|integer');
                    $validator->addRule("{$attribute}.{$key}.remark", 'required|string');
                    $validator->addRule("{$attribute}.{$key}.action", 'required|string|in:create,update,delete');
                }
            }]
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $user = User::where('userid', $id)
            ->whereHas('user_roles', function (Builder $query){
                $query->where('rolename', '=', 'Student');
            })->with(['student_records', function(Builder $query){
                $query->with(['subjects_taken', function(Builder $query){
                    $query->with('subjects')
                        ->with('semesters')
                        ->with(['curriculum'], function(Builder $query){
                            $query->with('program')->get();
                          })
                        ->get();
                }])->get();
            }])->first();

        if($user) {

            $user->student_record()
                ->update([
                    'student_no' => $request->student_no,
                    'year_level' => $request->year_level,
                    'status' => $request->status,
                ]);
            $studentRecord = $user->student_record;
        if (!empty($request->subjects_taken)) {

            foreach ($request->subjects_taken as $subject) {
                $action = $subject['action'];
                $subjectid = $subject['subjectid'];
                $taken_at = $subject['taken_at'];
                $remark = $subject['remark'];

                switch ($action) {
                    case 'create':
                        $studentRecord->subjects_taken()->create([
                            'srid' => $studentRecord->srid,
                            'subjectid' => $subjectid,
                            'taken_at' => $taken_at,
                            'remark' => $remark,
                        ]);
                        break;
                    case 'update':
                        // Check for both student_record_id and subjectid for update
                        $existingSubjectTaken = $studentRecord->subjects_taken()
                            ->where('srid', $studentRecord->srid)
                            ->where('subjectid', $subjectid)
                            ->first();
                        if ($existingSubjectTaken) {
                            $existingSubjectTaken->update([
                                'taken_at' => $taken_at ?? $existingSubjectTaken->taken_at,
                                'remark' => $remark ?? $existingSubjectTaken->remark,
                            ]);
                        } else {
                            return response()->json([
                                ['status' => 'error'],
                                'message' => "Subject_taken record with student_record_id: {$studentRecord->srid} and subject_id: {$subjectid} not found for updating",
                            ], 400);
                        }
                        break;
                    case 'delete':
                        $subjectTakenToDelete = $studentRecord->subjects_taken()
                            ->where('srid', $studentRecord->srid)
                            ->where('subjectid', $subjectid)
                            ->first();

                        if ($subjectTakenToDelete) {
                            $subjectTakenToDelete->delete();
                        } else {
                            return response()->json([
                                'status' => 'subject to delete not found',
                            ], 404);
                        }
                        break;
                    default:
                        return response()->json(['status' => 'invalid_action'], 400);
                }
            }
        }

            return response()->json([
                ['status' => 'success'],
                $user
            ], 200);
        }

        return response()->json([
            'status' => 'not found',
        ], 404);
    }

    public function delete(Request $request, String $id){
        $user = User::where('userid', $id)
            ->whereHas('user_roles', function (Builder $query){
                $query->where('rolename', '=', 'Student');
            })->with(['student_records', function(Builder $query){
                $query->with(['subjects_taken', function(Builder $query){
                    $query->with('subjects')
                        ->with('semesters')
                        ->with(['curriculum'], function(Builder $query){
                            $query->with('program')->get();
                          })
                        ->get();
                }])->get();
            }])->first();

        if($user) {
            return response()->json([
                ['status' => 'success'],
                $user->student_record()
                    ->delete()
            ], 200);
        }

        return response()->json([
            'status' => 'not found',
        ], 404);
    }

}
