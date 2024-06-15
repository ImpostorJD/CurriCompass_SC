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
use App\ReactPHP\RemainingSubAsync;
use App\ReactPHP\SegregateCourseAsync;
use App\ReactPHP\SortByCurriculumSubjectAsync;

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
        //$segregateCoursePromise = SegregateCourseAsync::courseClustering($courseAvailability);
        $courseAvailability = SortByCurriculumSubjectAsync::courseSorting($courseAvailability);
        //dd($courseAvailabilitySorted);
        //retrieve segregated course availability
        //$segregatedCourse = $this->collectPromises($segregateCoursePromise);
        //dd($segregatedCourse);
        //loop through this
        $time_range = [
            '8-11' => ['8-10', '8-11', '10-12'],
            '11-2' => ['10-12', '11-2', '1-3'],
            '2-5' => ['1-3', '2-5', '3-5'],
        ];

        // identify remaining lab subjects
        //$remainingLab = RemainingLabAsync::getRemainingLab($subjectsNotTaken);
        $dayPairings = ['M-Th', 'T-F', 'W-S'];

        $remainingSub = RemainingSubAsync::getRemainingSubCount($subjectsNotTaken, $targetStudent);
        $enlistment = Enlistment::create([
            'srid' => $targetStudent->srid,
            'cid' => $targetStudent->cid,
            'year_level_id' => $targetStudent->year_level_id,
            'semsyid' => $currentsemsy->semsyid,
        ]);

        // Process enlistment
        $this->processEnlistment($targetStudent, $dayPairings, $courseAvailability, $enlistment, $time_range, $remainingSub);

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
    // private function processEnlistment($targetStudent, $segregatedCourse, $enlistment, $timeRangeMap, $remainingLab)
    // {
    //     $minLabSubjects = $targetStudent->year_level_id < 4 ? 2 : $remainingLab;
    //     $maxUnits = $targetStudent->year_level_id < 4 ? 21 : PHP_INT_MAX;
    //     $unitCount = 0;
    //     $enlistedLabs = 0;
    //     $enlistedSubjects = [];
    //     $flattenedTimeRange = $this->flattenArrayUnique($timeRangeMap);
    //     // dd($segregatedCourse);
    //     foreach($flattenedTimeRange as $time) { //iterate through time

    //         foreach ($segregatedCourse as $dayPairingCourses) { //loop through mth tf ws

    //             foreach ($dayPairingCourses as $subjectCode) { //loop through each courses\

    //                 if (!array_key_exists($subjectCode, $enlistedSubjects)) { //if subject is not within the enlistmnet subject, proceed
    //                     $subject = Subjects::where('subjectcode', $subjectCode)->first();
    //                     $availability = CourseAvailability::where('subjectid', $subject->subjectid)
    //                         ->where('time', $time)
    //                         ->first();
    //                     if ($availability != null && $this->checkAvailabilityLimit($availability)) {
    //                         $subjectUnits = $subject->subjectcredits;
    //                         $isLab = floatval($subject->subjecthourslab) > floatval($subject->subjecthourslec);
    //                         //dd($isLab);

    //                         $subjectOverlap = false;
    //                         foreach ($enlistedSubjects as $enlistedSub => $enlistedTimeDay) {
    //                             if ($this->isDayTimeOverlap($enlistedTimeDay, [$availability->time, $availability->days], $timeRangeMap)) {
    //                                 $subjectOverlap = true;
    //                                 break;
    //                             }
    //                         }

    //                         if (!$subjectOverlap && ($isLab == false || $enlistedLabs < $minLabSubjects) && $unitCount + $subjectUnits < $maxUnits){
    //                             EnlistmentSubjects::create([
    //                                 'peid' => $enlistment->peid,
    //                                 'caid' => $availability->caid,
    //                             ]);

    //                             $enlistedSubjects[$subjectCode] = [$availability->time, $availability->days];
    //                             $unitCount += $subjectUnits;

    //                             if ($isLab) {
    //                                 $enlistedLabs++;
    //                             }
    //                         }


    //                     }


    //                 }

    //             }
    //         }

    //         if ($unitCount >= $maxUnits) {
    //             break; // Stop if max units are met
    //         }

    //     }

    // }
    private function processEnlistment($targetStudent, $dayPairings, $courses, $enlistment, $timeRangeMap, $remainingSub)
    {
        $maxUnits = $targetStudent->year_level_id < 4 ? 21 : PHP_INT_MAX; // Max units for 1st-3rd year, unlimited for 4th year
        $unitCount = 0;
        $enlistedSubjects = [];
        $flattenedTimeRange = $this->flattenArrayUnique($timeRangeMap);
        // Debug: Initial values
        // echo "max units $maxUnits";
        // echo "Initial unitCount: $unitCount \n, remainingSub: $remainingSub \n";

        // Loop while unit constraints for 1st-3rd year or remaining subjects for 4th year
        while (($targetStudent->year_level_id < 4 && $unitCount < $maxUnits) || ($targetStudent->year_level_id == 4 && $remainingSub > 0)) {

            $subjectEnlistedThisIteration = false; // Track if any subject was enlisted this iteration

            foreach ($flattenedTimeRange as $time) {
                foreach ($dayPairings as $dayPairing) { // iterate over M-Th, T-F, W-S
                    foreach ($courses as $subjectId) { // iterate over subjects
                        $subject = Subjects::where('subjectcode', $subjectId)->first();
                        // echo "current subject: $subjectId";
                        if (!array_key_exists($subjectId, $enlistedSubjects)) {
                            $availability = CourseAvailability::where('subjectid', $subject->subjectid)
                                ->where('time', $time)
                                ->where('days', $dayPairing)
                                ->first();

                            if ($availability != null && $this->checkAvailabilityLimit($availability)) {
                                $subjectUnits = $subject->subjectcredits;

                                // Check for overlap with already enlisted subjects
                                $subjectOverlap = false;
                                foreach ($enlistedSubjects as $enlistedSub => $enlistedTimeDay) {
                                    if ($this->isDayTimeOverlap($enlistedTimeDay, [$availability->time, $availability->days], $timeRangeMap)) {
                                        $subjectOverlap = true;
                                        // echo "subject $subjectId overlaps: $subjectOverlap";
                                        break;
                                    }
                                }

                                if (!$subjectOverlap && $unitCount + $subjectUnits <= $maxUnits) {
                                    if (!EnlistmentSubjects::where('peid', $enlistment->peid)
                                        ->where('caid', $availability->caid)
                                        ->exists()) {
                                        EnlistmentSubjects::create([
                                            'peid' => $enlistment->peid,
                                            'caid' => $availability->caid,
                                        ]);

                                        $enlistedSubjects[$subjectId] = [$availability->time, $availability->days];
                                        $unitCount += $subjectUnits;
                                        $remainingSub--;

                                        $subjectEnlistedThisIteration = true; // Mark that a subject was enlisted
                                    }
                                }
                            }
                        }
                    }
                    if ($subjectEnlistedThisIteration) {
                        break; // Exit the loop if we enlisted a subject
                    }
                }
                if ($subjectEnlistedThisIteration) {
                    break; // Exit the loop if we enlisted a subject
                }
            }

            // Debug: Log after each iteration
            // echo "After iteration: unitCount: $unitCount, remainingSub: $remainingSub\n";

            // foreach ($enlistedSubjects as $s => $timeDay){
            //     echo "subject: $s";
            //     echo "time: $timeDay[0]";
            //     echo "time: $timeDay[1]";

            // }
            // if (!$subjectEnlistedThisIteration) {
            //     // If no subjects were enlisted in this iteration, exit the loop to avoid infinite looping
            //     break;
            // }
        }
    }


    private function isDayTimeOverlap($enlistedTimeDay, $timeDay, $timeRange)
    {
        $checkOverlap = $this->getTimeRangeKeys($enlistedTimeDay[0], $timeRange); //get keys for time range for checking later

        $timeOverlap = false;
        foreach($checkOverlap as $t){
            if(in_array($timeDay[0], $timeRange[$t])){
                $timeOverlap = true;
                break;
            }
        }
        //$timeOverlap = $enlistedTimeDay[0] === $timeDay[0];
        $dayOverlap = $enlistedTimeDay[1] === $timeDay[1];

        return $timeOverlap && $dayOverlap; // Both time and day must overlap
    }
    private function flattenArrayUnique($timeRangeMap)
    {
        $flattened = [];
        foreach ($timeRangeMap as $key => $times) {
            foreach ($times as $time) {
                $flattened[] = $time;
            }
        }
        return array_unique($flattened); // Ensure unique times
    }

    private function getTimeRangeKeys($timeValue, $timeRangeMap) {
        $keys = [];

        foreach ($timeRangeMap as $key => $times) {
            if (in_array($timeValue, $times)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    private function checkAvailabilityLimit($availability)
    {
        if($availability->section_limit == 0) return true;
        // Count current enrollments in this availability
        if ($availability->section_limit == 0) return true; //experimental change, should return if section limit is 0
        $currentEnrolledCount = EnlistmentSubjects::where('caid', $availability->caid)->count();
        return $currentEnrolledCount < $availability->section_limit;
    }
}
