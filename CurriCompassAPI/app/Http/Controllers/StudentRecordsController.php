<?php

namespace App\Http\Controllers;

use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

//TODO: Implement ROLE BASED ACCESS
//TODO: FIX UPDATE
//TODO: Add documentation
class StudentRecordsController extends Controller
{
    public function index(){
        return response()->json([
            ['status' => 'success'],
            User::whereHas('user_roles', function($query){
               $query->where('rolename', '=', 'Student');
            })->with(['student_record' => function($query){
                $query->with(['curriculum' => function($query){
                    $query->with('program');
                    $query->with('curriculum_subjects');
                }]);

            }])->get()
        ], 200);
    }

    public function show(Request $request, String $id){
        $user = User::whereHas('user_roles', function ($query){
                $query->where('rolename', '=', 'Student');
            })->with(['student_record' => function($query) use ($id){
                $query->where('student_no', $id);
                $query->with(['subjects_taken' => function($query){
                    $query->with('subjects');
                }]);
                $query->with(['curriculum' => function($query){
                    $query->with('program');
                    $query->with(['curriculum_subjects' => function($query){
                        $query->with('subjects');
                    }]);
                  }]);
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

    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            'userfname' => ['required','string','max:255'],
            'userlname' => ['required','string','max:255'],
            'usermiddle' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255'],
            'contactno' => ['required','string', 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/'],
            'password' => ['required','string'],
            'roles' => ['required','array'],
            'roles.*.roleid' => ['required', 'integer'],
            "studentid" => ['required', 'string'],
            "status" => ['required','string'],
        ]);

        if($validate->fails()){
            return response()
                ->json([
                    ['status' => 'bad request'],
                    $validate->errors()
                ], 400);
        }

        $conflict_errors = [];
        if(User::where('email', $request->email)->first() != null) {
            $conflict_errors['email'] = "email is already in use.";
        }

        $existing_user = User::whereHas(
            'student_record', function($query) use ($request) {
            $query->where('student_no', $request->studentid);
        })->first();

        if($existing_user != null) {
            $conflict_errors["studentid"] = "Student ID is already in use.";
        }

        if(sizeof($conflict_errors) > 0){
            return response()
                ->json([
                    ['status' => 'conflict'],
                    $conflict_errors
                ], 409);
        }


        $user = User::create([
            'userfname' => $request->userfname,
            'userlname' => $request->userlname,
            'usermiddle' => $request->usermiddle,
            'contact_no' => $request->contactno,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        foreach($request->roles as $role) {
            $user->user_roles()->attach($role);
        }

        return response()->json([
            ['status' => 'student record created successfully.'],
            StudentRecord::create([
                'userid' => $user->userid,
                'year_level' => null,
                'status' => $request->status,
                'student_no' => $request->studentid,
                'cid' => null,
            ])
        ], 200);
    }

    //TODO: Fix the update
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
                $remark = $subject['remark'];

                switch ($action) {
                    case 'create':
                        $studentRecord->subjects_taken()->create([
                            'srid' => $studentRecord->srid,
                            'subjectid' => $subjectid,
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

    public function destroy(Request $request, String $id){
        $user = User::where('userid', $id)->first();
        if($user) {
            return response()->json([
                ['status' => 'success'],
                $user->delete()
            ], 200);
        }
        return response()->json([
            'status' => 'not found',
        ], 404);
    }

}
