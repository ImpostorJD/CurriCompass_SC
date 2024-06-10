<?php

namespace App\Http\Controllers;

use App\Jobs\CalculateSimilarityJob;
use App\Jobs\CourseAvailabilityCompareJob;
use App\Jobs\CourseClusteringJob;
use App\Jobs\IdentifyMissingCourseJob;
use App\Jobs\IdentifyRemainingLabJob;
use App\Jobs\PredictiveGradeJob;
use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\Enlistment;
use App\Models\EnlistmentSubjects;
use App\Models\SemSy;
use App\Models\StudentRecord;
use App\Models\Subjects;
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

        return response()->json([
            ['status' => 'success'],
            Enlistment::where('semsyid', $currentsemsy),
        ], 200);
    }

    public function store(Request $request)
    {
        //validate srid of target user
        $validate = Validator::make($request->all(), [
            'srid' => ['required', 'integer']
        ]);

        if($validate->fails()){
            return response()->json(["status" => "student record is missing"], 400);
        }

        $currentsemsy = SemSy::orderBy('semsyid', 'desc')->first();

        //Retrieve curriculum and student records synchronously
        $targetStudent = StudentRecord::where('srid', $request->srid)
            ->first();

        if($targetStudent == null){
            return response()->json(["status" => "not found"], 404);
        }

        if(Enlistment::where('srid', $targetStudent->srid)->where('semsyid', $currentsemsy->semsyid)->first() != null){
            return response()->json(["status" => "record already exists"], 409);
        }
        //get reference students
        $referenceStudents = StudentRecord::where('cid', $targetStudent->cid)
            ->where('year_level_id', ">=", $targetStudent->year_level_id)
            ->get();

        //calculate euclidean distance similarity asynchronously
        $similarityJobs = [];
        foreach($referenceStudents as $ref){
            $similarityJobs[] = CalculateSimilarityJob::dispatch($targetStudent, $ref);
        }

        //get the similarity index results
        $euclideanDistance = $this->collectJobResults($similarityJobs);

        //sort descending (highest to lowest 1 to 0)
        arsort($euclideanDistance);

        //retrieve courses from the curriculum
        $curriculum = Curriculum::where('cid', $targetStudent->cid)
            ->with('curriculum_subjects')
            ->first();

        //retrieve courses with the following criteria: failed, withdrawn
        //handle asynchronously
        $subjectJobs = [];
        foreach($curriculum->curriculum_subjects as $course) {
            $subjectJobs[] = IdentifyMissingCourseJob::dispatch($course->subjectid, $targetStudent);
        }

        //get all subjects taken
        $rawSubjectsNotTaken = $this->collectJobResults($subjectJobs);

        //filter out all null value
        $subjectsNotTaken = array_filter($rawSubjectsNotTaken, function($value) {
            return !is_null($value);
        });

        //predict grade weighted average asynchronously
        $weightedGradeJob = [];
        foreach($subjectsNotTaken as $sub){
            $weightedGradeJob[] = PredictiveGradeJob::dispatch($sub, $euclideanDistance);
        }

        //retrieve result of predicted GWA
        $predictedGwa = $this->collectJobResults($weightedGradeJob);
        asort($predictedGwa);

        $courseAvailabilityJob = [];
        foreach($weightedGradeJob as $sub){
            $courseAvailabilityJob[] = CourseAvailabilityCompareJob::dispatch($sub, $targetStudent);
        }

        //retrieve course availability
        $rawCourseAvailability = $this->collectJobResults($courseAvailabilityJob);

        //filter null
        $courseAvailability = array_filter($rawCourseAvailability, function($value) {
            return !is_null($value);
        });

        //segregate asynchronously
        $segregateCourseJob = [];
        foreach($courseAvailability as $course){
            $segregateCourseJob[] = CourseClusteringJob::dispatch($course);
        }

        //retrieve segregated course availability
        $segregatedCourse = $this->collectJobResults($segregateCourseJob);

        //loop through this
        $time_range = [
            '8-11' => ['8-10', '8-11', '10-12'],
            '11-2' => ['10-12', '11-2', '1-3'],
            '2-5' => ['1-3', '2-5', '3-5'],
        ];

        $enlistment = Enlistment::create([
            'srid' => $targetStudent->srid,
            'cid' => $targetStudent->cid,
            'year_level_id' => $targetStudent->year_level_id,
            'semsyid' => $currentsemsy,
        ]);

        // identify remaining lab subjects
        $remainingLabJob = IdentifyRemainingLabJob::dispatch($subjectsNotTaken);

        // Process enlistment
        $this->processEnlistment($targetStudent, $segregatedCourse, $enlistment, $time_range, $remainingLabJob->get());

        //return response
        return response()->json([
            ['status' => 'success'],
        ], 200);
    }

    public function show(Request $request, String $id)
    {
        $res = Enlistment::where('peid', $id)->first();

        if($res){
            return response()->json([
                ['status' => 'success'],
                $res
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ['message' => 'resource not found']
        ], 404);
    }

    public function update(Request $request, String $id)
    {
        $enlistment = Enlistment::where('peid', $id)->first();
        $validate = Validator::make($request->all(), [
            'enlistment_subjects' => ['required' => 'array'],
            'enlistment_subjects*.caid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([
                ['status' => 'bad request'],
            ], 400);
        }
        if($enlistment) {
          //update logic
          $enlistment->enlistment_subjects->delete();

          foreach($request->enlistment_subjects as $es) {
            EnlistmentSubjects::create([
                'peid' => $enlistment->peid,
                'caid' => $es['caid'],
            ]);
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

    /**
     * 06/10/2024
     *
     * This function is a helper function to retrieve a collection of results from jobs (asynchronously)
     */
    private function collectJobResults($jobs)
    {
        $results = [];
        foreach ($jobs as $job) {
            $results[] = $job->get();
        }
        return $results;
    }

    /**
     * This function is a helper function to process the actual enlistment creation.
     */
    private function processEnlistment($targetStudent, $segregatedCourse, $enlistment, $timeRangeMap, $remainingLab)
    {
        $minLabSubjects = $targetStudent->year_level_id < 4 ? min(3, $remainingLab) : $remainingLab;
        $maxUnits = $targetStudent->year_level_id < 4 ? 21 : PHP_INT_MAX;
        $unitCount = 0;
        $enlistedLabs = 0;
        $enlistedSubjects = [];

        foreach ($timeRangeMap as $timeKey => $overlappingTimes) {
            foreach ($segregatedCourse as $dayPairingCourses) {
                foreach ($dayPairingCourses as $subjectId) {
                    if (in_array($subjectId, $enlistedSubjects)) {
                        continue;
                    }

                    $availability = CourseAvailability::where('subjectid', $subjectId)
                        ->whereIn('time', $overlappingTimes)
                        ->first();

                    if ($availability && $this->checkAvailabilityLimit($availability)) {
                        $subject = Subjects::find($subjectId);
                        $subjectUnits = $subject->subjectcredits;
                        $isLab = $subject->subjecthourslab > $subject->subject->subjecthourslec;

                        if ($unitCount + $subjectUnits <= $maxUnits && (!$isLab || $enlistedLabs < $minLabSubjects)) {
                            EnlistmentSubjects::create([
                                'peid' => $enlistment->peid,
                                'caid' => $availability->caid,
                            ]);

                            $enlistedSubjects[] = $subjectId;
                            $unitCount += $subjectUnits;

                            if ($isLab) {
                                $enlistedLabs++;
                            }
                        }
                    }
                }
            }

            if ($unitCount >= $maxUnits) {
                break; // Stop if max units are met
            }
        }
    }

    private function checkAvailabilityLimit($availability)
    {
        // Count current enrollments in this availability
        $currentEnrolledCount = EnlistmentSubjects::where('caid', $availability->caid)->count();
        return $currentEnrolledCount < $availability->section_limit;
    }
}
