<?php

namespace App\Http\Controllers;

use App\Jobs\MarkStudentAsInactive;
use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\Enlistment;
use App\Models\SchoolYear;
use App\Models\Semesters;
use App\Models\SemSy;
use App\Models\StudentRecord;
use App\Models\User;
use App\ReactPHP\CompareSubjectTakenAndEnlistedSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use React\Promise;

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

    public function generateLatest(){

        if(SemSy::all()->count() == 0){
            $school_year = SchoolYear::orderBy('sy', 'desc')->first();
            $semester = Semesters::orderBy('semid', 'asc')->first();

            return response()->json([
                SemSy::create([
                    'sy'=> $school_year->sy,
                    'semid'=> $semester->semid
                ])
            ],200);
        }else{

            $createable = true;

            $semsy = SemSy::orderBy('semsyid', 'desc')
                ->with('semester')
                ->with('school_year')
                ->first();

            $latestSy = SchoolYear::where('sy', '>', $semsy->sy)->first();

            if($semsy->semid == 3){
                if($latestSy == null){
                    $messages['school_year'] = "Succeeding school year is missing.";
                    $createable = false;
                }
            }

            if(!$createable) return response()->json([
                ['status', 'bad request'],
                $messages
            ], 400);

            $latest = SemSy::create([
                'sy' => $semsy->semid != 3 ? $semsy->sy : $latestSy->sy,
                'semid' => $semsy->semid != 3 ? $semsy->semid + 1 : 1,
            ]);

            $curricula = Curriculum::all();

            foreach ($curricula as $curriculum) {

                $curriculum_subjects = CurriculumSubjects::where('cid', $curriculum->cid)->get();

                foreach ($curriculum_subjects as $csubject) {
                    if ($csubject->semid == $latest->semid) {

                        $secLimit = $csubject->hourslab > $csubject->hourslec ? 45 : 50;

                        if(!CourseAvailability::where('coursecode',  $csubject->coursecode)->where('semsyid', $latest->semsyid)->first()){
                            CourseAvailability::create([
                                'coursecode' => $csubject->coursecode,
                                'semsyid' => $latest->semsyid,
                                'section_limit' => $secLimit,
                                'lab' => $csubject->hourslab > $csubject->hourslec,
                            ]);
                        }

                    }
                }
            }

            return response()->json([
                //job: use promise to iterate over students that has no enlistment, mark as inactive
                // MarkStudentAsInactive::dispatch($semsy),


            ],200);
        }

    }

    public function latest(){

        return response()->json([
            ['status' => 'success'],
            SemSy::orderBy('semsyid', 'desc')
            ->with('semester')
            ->with('school_year')
            ->first()
        ], 200);
    }

    private function collectPromises($promises){
        $resolvedResults = [];
        Promise\all($promises)->then(function($res) use (&$resolvedResults) {
            $resolvedResults = $res;
        });
        return $resolvedResults;
    }

    private function flattenArray($arr){
        $flattenedArray = [];
        foreach ($arr as $item) {
            foreach ($item as $key => $value) {
                $flattenedArray[$key] = $value;
            }
        }

        return $flattenedArray;
    }
}
