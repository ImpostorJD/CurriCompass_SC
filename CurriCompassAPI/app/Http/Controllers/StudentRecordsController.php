<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

//TODO: Add documentation
class StudentRecordsController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index(){
        return response()->json([
            ['status' => 'success'],
            User::whereHas('user_roles', function($query){
               $query->where('rolename', '=', 'Student');
            })->with(['student_record' => function($query){
                $query->with('school_year');
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
                    $query->with('school_year');
                }]);
                $query->with(['curriculum' => function($query){
                    $query->with('program');
                    $query->with('school_year');
                    $query->with(['curriculum_subjects' => function($query){
                        $query->with('subjects');
                        $query->with('semesters');
                    }]);
                  }]);
                $query->with('school_year');
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

    public function update(Request $request, String $id){

        $validate = Validator::make($request->all(), [
            'userfname' => ['required','string','max:255'],
            'userlname' => ['required','string','max:255'],
            'usermiddle' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255'],
            'contact_no' => ['required','string', 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/'],
            "studentid" => ['required', 'string'],
            'sy'=> ['required','integer'],
            "status" => ['required','string'],
            "program" => ['required','integer'],
            "specialization" => ['nullable','string'],
            "subjects_taken" => ['nullable','array'],
            "subjects_taken*.subjectid" => ['required','integer'],
            "subjects_taken*.taken_at" => ['required','string'],
            "subjects_taken*.sy" => ['required','integer'],
            "subjects_taken*.remark" => ['required','string'],
        ]);

        if($validate->fails()){
            return response()
            ->json([
                ['status' => 'bad request'],
                $validate->errors()
            ], 400);
        }

        $user = User::with('student_record')
            ->where('userid', $id)
            ->first();


        if(!$user){
            return response()->json([
                'status' => 'not found',
            ], 404);
        }

        $conflict_errors = [];
        if($user->email != $request->email &&
        User::where('email', $request->email)->first() != null) {
            $conflict_errors['email'] = "email is already in use.";
        }


        if($user->student_record->student_no != $request->studentid &&
        User::whereHas(
            'student_record', function($query) use ($request) {
            $query->where('student_no', $request->studentid);
        })->first() != null) {
            $conflict_errors["studentid"] = "Student ID is already in use.";
        }

        if(sizeof($conflict_errors) > 0){
            return response()
                ->json([
                    ['status' => 'conflict'],
                    $conflict_errors
                ], 409);
        }

        $user->update([
            'userfname' => $request->userfname,
            'userlname' => $request->userlname,
            'usermiddle' => $request->usermiddle,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
        ]);

        $curriculum = Curriculum::whereHas('program', function($query) use ($request){
            $query->where('programid', $request->program)
            ->where('specialization', $request->specialization);
        })->first();

        $user->student_record()->update([
            'year_level' => $request['year_level'],
            'status' => $request['status'],
            'student_no' => $request['studentid'],
            'cid' => $curriculum['cid'],
            'sy' => $request['sy']
        ]);

        $user->student_record->subjects_taken()->delete();

        foreach($request->subjects_taken as $subject){
            $user->student_record->subjects_taken()->insert([
                'subjectid' => $subject['subjectid'],
                'taken_at' => $subject['taken_at'],
                'remark' => $subject['remark'],
                'srid' => $user->student_record->srid,
                'sy' => $subject['sy'],
            ]);
        }

        return response()->json([
            ['status' => 'student record created successfully.'],
            $user
        ], 200);
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
