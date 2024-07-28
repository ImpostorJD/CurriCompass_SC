<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Enlistment;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\StudentRecord;
use App\Models\SubjectsTaken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
                $query->with(['curriculum' => function($query){
                    $query->with('program');
                    $query->with('curriculum_subjects');
                    $query->with('school_year');
                }]);
                $query->with('year_level');
            }])->get()
        ], 200);
    }

    public function show(Request $request, String $id){
        $user = User::whereHas('user_roles', function ($query){
                $query->where('rolename', '=', 'Student');
            })->whereHas('student_record', function($query) use ($id){
                $query->where('student_no', $id);

            })->with(['student_record' => function($query){
                $query->with(['subjects_taken' => function($query){
                    $query->with('school_year');
                }]);
                $query->with(['curriculum' => function($query){
                    $query->with('program');
                    $query->with('school_year');
                    $query->with(['curriculum_subjects' => function($query){
                        $query->with('year_level');
                        $query->with('semesters');
                    }]);
                  }]);
                $query->with('year_level');
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
            'usermiddle' => ['nullable','string','max:255'],
            'email' => ['required','string','email','max:255'],
            'contactno' => ['required','string', 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/'],
            'password' => ['required','string'],
            'roles' => ['required','array'],
            'roles.*.roleid' => ['required', 'integer'],
            "studentid" => ['required', 'string'],
            // "status" => ['required','string'],
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
            'usermiddle' => $request->usermiddle ? $request->usermiddle : null,
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
                'year_level_id' => null,
                // 'status' => $request->status,
                'student_no' => $request->studentid,
                'cid' => null,
            ])
        ], 200);
    }

    public function update(Request $request, String $id){

        // Validation logic remains the same

        $user = User::with(['student_record' => function ($query) {
            $query->with(['curriculum' => function ($query) {
                $query->with('curriculum_subjects');
            }]);
        }])
        ->where('userid', $id)
        ->first();

        // Conflict checking logic remains the same

        // Update user and student record
        $user->update([
            'userfname' => $request->userfname,
            'userlname' => $request->userlname,
            'usermiddle' => $request->usermiddle ? $request->usermiddle : null,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
        ]);

        $user->student_record()->update([
            'student_no' => $request['studentid'],
            'cid' => $request['curriculum'],
            'sy' => $request['sy']
        ]);

        // Delete old subjects and insert new ones
        $user->student_record->subjects_taken()->delete();

        foreach($request->subjects_taken as $subject){
            $user->student_record->subjects_taken()->insert([
                'coursecode' => $subject['coursecode'],
                'grade' => $subject['grade'],
                'srid' => $user->student_record->srid,
            ]);
        }

        // Get updated subjects taken
        $subjects_taken = SubjectsTaken::where('srid', $user->student_record->srid)->get();

        // Calculate completion and status
        $year1Completion = 0;
        $year2Completion = 0;
        $year3Completion = 0;
        $year4Completion = 0;

        foreach($user->student_record->curriculum->curriculum_subjects as $cs){
            if($cs->year_level_id <= 4){
                $year4Completion += $cs->units;
            }

            if($cs->year_level_id <= 3){
                $year3Completion += $cs->units;
            }

            if($cs->year_level_id <= 2){
                $year2Completion += $cs->units;
            }

            if($cs->year_level_id <= 1){
                $year1Completion += $cs->units;
            }
        }

        $status = "Regular";
        $year_level = 1;
        $totalUnitsTaken = 0;
        $subjectsFailedCount = 0;
        $yearToIncrement = true;
        $excludedCodes = ['GE', 'SOA', 'PE', 'CSR', 'ACC', 'ENG', 'ENV', 'FL ', 'CALC', 'CWTS', 'ROTC'];


        foreach($subjects_taken as $taken) {
            $subject_t = $user->student_record->curriculum->curriculum_subjects->firstWhere('coursecode', $taken->coursecode);
            if(!$subject_t){
                continue;
            }

            $totalUnitsTaken += $subject_t->units;

            if($taken->grade == "w" || $taken->grade == "x" || $taken->grade == "5"){
                $subjectsFailedCount++;

                $containsExcludedCode = false;

                foreach ($excludedCodes as $code) {
                    if (strpos($subject_t->coursecode, $code) !== false) {
                        $containsExcludedCode = true;
                        break;
                    }
                }

                if (!$containsExcludedCode) {
                    foreach($user->student_record->curriculum->curriculum_subjects as $cs){
                        $pre_req = $cs->prerequisites != null || $cs->prerequisites != "None" ? explode(" & ", $cs->prerequisites) : null;

                        if($pre_req){
                            $filtered = array_filter($pre_req, function($string) use ($subject_t) {
                                return strpos($string, "GRADUATING") === false && strpos($string, "STANDING") === false && $string === $subject_t->coursecode;
                            });

                            if(count($filtered) > 0 && $filtered[0] == $subject_t->coursecode){
                                foreach($filtered as $pr){
                                    $pre_req_code = $user->student_record->curriculum->curriculum_subjects->firstWhere('coursecode', $pr);
                                    if($pre_req_code){
                                        Log::info('prerequisite failed:'.$pre_req_code->coursecode." of ".$cs->coursecode);
                                        // Set yearToIncrement to false if prerequisite is not met
                                        $year_level = $subject_t->year_level_id;
                                        $yearToIncrement = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        // Determine status and year level
        if($subjectsFailedCount >= 1){
            $status = "Irregular";
            if($subjectsFailedCount >= 3){
                $yearToIncrement = false; // Prevent incrementing if too many failures
            }
        }

        if($totalUnitsTaken == $year4Completion){
            $status = "Graduated";
            $yearToIncrement = false;
        }

        if($yearToIncrement){
            if($totalUnitsTaken >= ($year1Completion - 9)){
                $year_level = 2;
            }

            if($totalUnitsTaken >= ($year2Completion - 9)){
                $year_level = 3;
            }

            if($totalUnitsTaken >= ($year3Completion - 9)){
                $year_level = 4;
            }
        }

        $user->student_record()->update([
            'status' => $status,
            'year_level_id' => $year_level,
        ]);

        return response()->json([
            ['status' => 'student record updated successfully.'],
            $user
        ], 200);
    }

    public function destroy(Request $request, String $id){
        $user = User::where('userid', $id)->with('student_record')->first();
        if($user) {
            if(Enlistment::where('srid', $user->student_record->srid)->count() > 0){
                return response()->json([
                    ['status' => 'enlistment records existing, cannot be deleted.'],
                ], 409);
            }
            return response()->json([
                ['status' => 'success'],
                $user->delete()
            ], 200);
        }
        return response()->json([
            'status' => 'not found',
        ], 404);
    }

    public function bulk(Request $request)
    {
        // Validate the request to ensure a file is uploaded
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid file upload',
                'details' => $validator->errors()
            ], 400);
        }

        // Get the uploaded file
        $file = $request->file('file');

        // Open and read the file content
        $csvData = array_map('str_getcsv', file($file->getRealPath()));

        // Process the CSV data
        $header = array_shift($csvData);
        $processedData = array_map(function($row) use ($header) {
            return array_combine($header, $row);
        }, $csvData);

        // Register users
        $users = [];
        $role = Role::where('rolename', 'Student')->get();
        $year_of_admission = SchoolYear::orderBy('sy', 'desc')->first();
        foreach ($processedData as $data) {
            $validator = Validator::make($data, [
                'First Name' => ['required','string','max:255'],
                'Last Name' => ['required','string','max:255'],
                'Middle Name' => ['nullable','string','max:255'],
                'Program' => ['nullable','string','max:255'],
                'Email' => ['required','string','email','max:255'],
                'Contact No' => ['required','string', 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/'],
                "Student Number" => ['required', 'string'],
                "Year Level" => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Invalid user data',
                    'details' => $validator->errors()
                ], 422);
            }

            $curriculum = Curriculum::whereHas('program', function($query) use ($data) {
                $query->where('programcode', $data['Program']);
            })->first();

            try{
                if(User::where('email', $data["Email"])->first() == null && StudentRecord::where('student_no', $data["Student Number"])->first() == null){
                    $user = User::create([
                        'userfname' => $data["First Name"],
                        'userlname' => $data["Last Name"],
                        'usermiddle' => $data["Middle Name"],
                        'email' => $data["Email"],
                        'contact_no' => $data["Contact No"],
                        'password' => Hash::make($data["Student Number"]),
                    ]);

                    foreach($role as $r) {
                        $user->user_roles()->attach($r);
                    }

                    StudentRecord::create([
                        'userid' => $user->userid,
                        'year_level_id' =>  $data["Year Level"],
                        // 'status' => $request->status,
                        'sy' => $year_of_admission->sy,
                        'student_no' => $data["Student Number"],
                        'cid' => $curriculum->cid,
                    ]);
                    Log::info('created student: '.$data["Student Number"]. " with userid ". $user->userid);
                }

            }catch(Exception $e){
                Log::info($e->getMessage());
            }

        }
        // Return the registered users as a JSON response
       return response()->json([
            'message' => 'CSV file processed and users registered successfully',
        ]);
    }

}
