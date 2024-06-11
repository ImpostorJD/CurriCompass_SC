<?php

namespace App\Http\Controllers;

use App\Jobs\CourseClusteringJob;
use App\Jobs\IdentifyRemainingLabJob;
use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\Enlistment;
use App\Models\EnlistmentSubjects;
use App\Models\SemSy;
use App\Models\StudentRecord;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use React\Promise;
use App\ReactPHP\CalculateSimilarityAsync;
use App\ReactPHP\CompareCourseAvailabilityAsync;
use App\ReactPHP\IdentifyMissingCourseAsync;
use App\ReactPHP\PredictiveGradeAsync;
use App\ReactPHP\RemainingLabAsync;
use App\ReactPHP\SegregateCourseAsync;

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
            'srid' => ['required', 'string']
        ]);

        if($validate->fails()){
            return response()->json(["status" => "student record is missing"], 400);
        }

        $currentsemsy = SemSy::orderBy('semsyid', 'desc')->first();

        //Retrieve curriculum and student records synchronously
        $targetStudent = StudentRecord::where('student_no', $request['srid'])
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
        $similarityPromises = [];
        foreach ($referenceStudents as $ref) {
            $similarityPromises[] = CalculateSimilarityAsync::calculateSimilarity($targetStudent, $ref);
        }

        $euclideanDistance = $this->collectPromises($similarityPromises);
        $euclideanDistance = $this->flattenArray($euclideanDistance);

        //sort descending (highest to lowest 1 to 0)
        arsort($euclideanDistance);

        $euclideanDistance = array_filter($euclideanDistance, function($value) {
            return $value != 0;
        });

        //retrieve courses from the curriculum
        $curriculum = Curriculum::where('cid', $targetStudent->cid)
            ->with('curriculum_subjects')
            ->first();

        //retrieve courses with the following criteria: failed, withdrawn
        //handle asynchronously
        $subjectPromises = [];
        foreach($curriculum->curriculum_subjects as $course) {
            $subjectPromises[] = IdentifyMissingCourseAsync::identifyMissingCourse($course->subjectid, $targetStudent);
        }

        //get all subjects taken
        $subjectsNotTaken = $this->collectPromises($subjectPromises);

        //filter out all null value
        $subjectsNotTaken = array_filter($subjectsNotTaken, function($value) {
            return !is_null($value);
        });

        //predict grade weighted average asynchronously
        $weightedGradePromise = [];
        foreach($subjectsNotTaken as $sub){
            $weightedGradePromise[] = PredictiveGradeAsync::gradedWeightAverage($sub, $euclideanDistance);
        }

        //retrieve result of predicted GWA
        $predictedGwa = $this->collectPromises($weightedGradePromise);
        $predictedGwa = $this->flattenArray($predictedGwa);
        asort($predictedGwa);

        $courseAvailabilityPromise = [];
        foreach($predictedGwa as $subid => $grade){
            $courseAvailabilityPromise[] = CompareCourseAvailabilityAsync::compareCourseAvailability($subid, $targetStudent);
        }

        //retrieve course availability
        $courseAvailability = $this->collectPromises($courseAvailabilityPromise);

        //filter null
        $courseAvailability = array_filter($courseAvailability, function($value) {
            return !is_null($value);
        });

        //segregate asynchronously
        $segregateCoursePromise = SegregateCourseAsync::courseClustering($courseAvailability);

        //retrieve segregated course availability
        $segregatedCourse = $this->collectPromises($segregateCoursePromise);

        //loop through this
        $time_range = [
            '8-11' => ['8-10', '8-11', '10-12'],
            '11-2' => ['10-12', '11-2', '1-3'],
            '2-5' => ['1-3', '2-5', '3-5'],
        ];


        // identify remaining lab subjects
        $remainingLab = RemainingLabAsync::getRemainingLab($subjectsNotTaken);

        $enlistment = Enlistment::create([
            'srid' => $targetStudent->srid,
            'cid' => $targetStudent->cid,
            'year_level_id' => $targetStudent->year_level_id,
            'semsyid' => $currentsemsy->semsyid,
        ]);

        // Process enlistment
        $this->processEnlistment($targetStudent, $segregatedCourse, $enlistment, $time_range, $remainingLab);

        //return response
        return response()->json([
            ['status' => 'success'],
            Enlistment::where('peid', $enlistment->peid)
                ->with(['enlistment_subjects' => function($query){
                    $query->with(['course_availability' => function($query){
                        $query->with('subjects');
                    }]);
                }])->first()
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
                        $isLab = $subject->subjecthourslab > $subject->subjecthourslec;

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
