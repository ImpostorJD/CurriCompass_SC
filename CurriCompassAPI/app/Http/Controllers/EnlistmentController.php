<?php

namespace App\Http\Controllers;

use App\Jobs\EnlistmentAlgorithm;
use App\Models\CourseAvailability;
use App\Models\Enlistment;
use App\Models\EnlistmentSubjects;
use App\Models\SemSy;
use App\Models\StudentRecord;
use App\Models\Subjects;
use App\Models\SubjectsTaken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Add documentation
class EnlistmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $currentsemsy = SemSy::orderBy('semsyid', 'desc')->first();
        $users = User::whereHas('user_roles', function($query){
            $query->where('rolename', '=', 'Student');
         })->whereHas('student_record', function($query){
            $query->where('status', '!=', 'Graduated');
            $query->where('status', '!=', 'Inactive');
            $query->where('status', '!=', null);
            $query->whereHas('curriculum');
         })
         ->with(['student_record' => function($query) use ($currentsemsy){
            $query->with(['curriculum' => function($query){
                $query->with('program');
            }]);

            $query->with(['enlistment' => function($query) use ($currentsemsy) {
                $query->where('semsyid', $currentsemsy->semsyid);
            }]);
            $query->with('year_level');
         }])->get();
        return response()->json([
            ['status' => 'success'],
            $users
        ], 200);
    }

    public function store(Request $request)
    {

        //validate srid of target user
        $validate = Validator::make($request->all(), [
            'srid' => ['required', 'string']
        ]);

        if($validate->fails()){
            return response()->json(["status" => "student record is missing"], 400);
        }

        //Retrieve curriculum and student records synchronously
        $targetStudent = StudentRecord::where('student_no', $request['srid'])
            ->first();

        if($targetStudent == null){
            return response()->json(["status" => "not found"], 404);
        }

        if($targetStudent->cid == null){
            return response()->json(["status" => "User is not assigned to a curriculum yet."], 400);
        }

        $currentsemsy = SemSy::orderBy('semsyid', 'desc')
            ->with('semester')
            ->with('school_year')
            ->first();

        if(CourseAvailability::where('semsyid', $currentsemsy->semsyid)->count() == 0){
            return response()->json(["status" => "No course available set for the current semester."], 400);
        }

        if(Enlistment::where('srid', $targetStudent->srid)
            ->where('semsyid', $currentsemsy->semsyid)
            ->first() != null){
            return response()->json(["status" => "record already exists"], 409);
        }

        $enlistment = Enlistment::create([
            'srid' => $targetStudent->srid,
            'cid' => $targetStudent->cid,
            'year_level_id' => $targetStudent->year_level_id,
            'semsyid' => $currentsemsy->semsyid,
        ]);
        EnlistmentAlgorithm::dispatch($targetStudent, $enlistment)->onQueue('high');

        //return response
        return response()->json([
            ['status' => 'success'],
            ['message' => 'enlistment creation in progress'],
        ], 200);
    }

    public function show(Request $request, String $id)
    {

        $currentsemsy = SemSy::orderBy('semsyid', 'desc')
            ->with('semester')
            ->with('school_year')
            ->first();

        $user = User::whereHas('student_record', function($query) use ($id){
            $query->where('student_no', $id);

        })->with(['student_record' => function($query) use ($currentsemsy) {
            $query->with('subjects_taken');
            $query->with(['enlistment' => function ($query) use ($currentsemsy){
                $query->where('semsyid', $currentsemsy->semsyid);
                $query->with(['enlistment_subjects' => function ($query) {
                    $query->with(['course_availability' => function ($query){
                        $query->with('subjects');
                    }]);
                }]);
            }]);
            $query->with(['curriculum' => function($query){
                $query->with('curriculum_subjects');
              }]);
            $query->with('year_level');
            $query->with('school_year');
        }])->first();

        if($user){
            $subjectsNotTaken = Subjects::whereHas('curriculumsubjects', function($query) use ($user){
                $query->where('cid', $user->student_record->cid);
                $query->whereNotIn('subjectid', $user->student_record->subjects_taken->pluck('subjectid')->toArray());
            })->count();

            return response()->json([
                ['status' => 'success'],
                $user,
                $currentsemsy,
                $subjectsNotTaken
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ['message' => 'resource not found']
        ], 404);
    }

    public function update(Request $request, String $id)
    {
        $validate = Validator::make($request->all(), [
            'enlistmentId' => ['required' => 'integer'],
            'subjects' => ['required' => 'array'],
            'subjects*.caid' => ['required', 'integer'],
            'subjects*.grade' => ['nullable', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([
                ['status' => 'bad request'],
            ], 400);
        }
        $enlistment = Enlistment::whereHas('student_record', function($query) use ($id){
            $query->where('student_no', $id);
        })->where('peid', $request->enlistmentId)->first();


        $currentsemsy = SemSy::orderBy('semsyid', 'desc')->first();
        $taken_at = $currentsemsy->semid == 1 ? "Sem 1" : ($currentsemsy->semid == 2 ? "Sem 2" : "Sem 3");
        if($enlistment) {
          //update logic
          $enlistment->enlistment_subjects()->delete();


          foreach($request->subjects as $es) {
            EnlistmentSubjects::create([
                'peid' => $enlistment->peid,
                'caid' => $es['caid'],
            ]);

            $studentRecord = StudentRecord::where('student_no', $id)->first();

            if($es['grade'] != null) {

                $remark = $es['grade'] == 1 ? "Excellent" :
                    ($es['grade'] == 1.25 || $es['grade'] == 1.50 ? "Very Good" :
                    ($es['grade'] == 1.75 || $es['grade'] == 2 || $es['grade'] == 2.25 ? "Good" :
                    ($es['grade'] == 2.5 ? "Fair" :
                    ($es['grade'] == 2.75 || $es['grade'] == 3 ? "Passing" : "Failed"))));

                $subjectTaken = SubjectsTaken::where('subjectid', $es['subjectid'])->where('srid',$studentRecord->srid)->first();
                if($subjectTaken) {
                    $subjectTaken->delete();
                }

                SubjectsTaken::create([
                    'srid' => $studentRecord->srid,
                    'subjectid' => $es['subjectid'],
                    'taken_at'=> $taken_at,
                    'grade' => $es['grade'],
                    'sy' => $currentsemsy->sy,
                    'remark' => $remark,
                ]);

            }
          }

        return response()->json([
            ['status' => 'success'],
            $enlistment
        ], 200);

        }
        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }

    public function destroy(Request $request, String $id)
    {
        $enlistment = Enlistment::where('peid', $id)->first();

        if($enlistment){
            return response()->json([
                ['status' => 'success'],
                $enlistment->delete()
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
        ], 404);

    }

}
