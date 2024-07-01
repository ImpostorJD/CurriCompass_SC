<?php

namespace App\Jobs;

use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\EnlistmentSubjects;
use App\Models\StudentRecord;
use App\Models\Subjects;
use App\ReactPHP\CalculateSimilarityAsync;
use App\ReactPHP\CompareCourseAvailabilityAsync;
use App\ReactPHP\IdentifyMissingCourseAsync;
use App\ReactPHP\PredictiveGradeAsync;
use App\ReactPHP\RemainingSubAsync;
use App\ReactPHP\SortByCurriculumSubjectAsync;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use React\Promise;

class EnlistmentAlgorithm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $targetStudent;
    private $enlistment;
    public function __construct($targetStudent, $enlistment)
    {
        $this->targetStudent = $targetStudent;
        $this->enlistment = $enlistment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //get reference students
        $referenceStudents = StudentRecord::where('cid', $this->targetStudent->cid)
            ->where('year_level_id', ">=", $this->targetStudent->year_level_id)
            ->get();

        //calculate euclidean distance similarity asynchronously
        $similarityPromises = array_map(
            fn($ref) => CalculateSimilarityAsync::calculateSimilarity($this->targetStudent, $ref),
            $referenceStudents->all()
        );

        $euclideanDistance = $this->collectPromises($similarityPromises);
        $euclideanDistance = $this->flattenArray($euclideanDistance);

        //sort descending (highest to lowest 1 to 0)
        arsort($euclideanDistance);

        $euclideanDistance = array_filter($euclideanDistance, function($value) {
            return $value != 0;
        });

        //retrieve courses from the curriculum
        $curriculum = Curriculum::where('cid', $this->targetStudent->cid)
            ->with('curriculum_subjects')
            ->first();

        //retrieve courses with the following criteria: failed, withdrawn
        //handle asynchronously
        $subjectPromises = array_map(
            fn($course) => IdentifyMissingCourseAsync::identifyMissingCourse($course->subjectid, $this->targetStudent),
            $curriculum->curriculum_subjects->all()
        );
        //get all subjects taken
        $subjectsNotTaken = $this->collectPromises($subjectPromises);

        //filter out all null value
        $subjectsNotTaken = array_filter($subjectsNotTaken, function($value) {
            return !is_null($value);
        });

        //predict grade weighted average asynchronously
        $weightedGradePromises = array_map(
            fn($sub) => PredictiveGradeAsync::gradedWeightAverage($sub, $euclideanDistance),
            $subjectsNotTaken
        );
        //retrieve result of predicted GWA
        $predictedGwa = $this->collectPromises($weightedGradePromises);
        $predictedGwa = $this->flattenArray($predictedGwa);
        asort($predictedGwa);

        $courseAvailabilityPromises = array_map(
            fn($subid, $grade) => CompareCourseAvailabilityAsync::compareCourseAvailability($subid, $this->targetStudent, $grade),
            array_keys($predictedGwa),
            $predictedGwa
        );

        //retrieve course availability
        $courseAvailability = $this->collectPromises($courseAvailabilityPromises);

        //filter null
        $courseAvailability = array_filter($courseAvailability, function($value) {
            return !is_null($value);
        });

        //flat course availability
        $courseAvailability = $this->flattenArray($courseAvailability);
        $courseAvailability = SortByCurriculumSubjectAsync::courseSorting($courseAvailability);

        //loop through this
        $time_range = [
            '8-11' => ['8-10', '8-11', '9-11', '10-12'],
            '11-2' => ['10-12', '11-2', '1-3'],
            '2-5' => ['1-3','2-4','2-5', '3-5'],
        ];


        // identify remaining lab subjects
        //$remainingLab = RemainingLabAsync::getRemainingLab($subjectsNotTaken);
        $dayPairings = ['M-Th', 'T-F', 'W-S'];

        $remainingSub = RemainingSubAsync::getRemainingSubCount($subjectsNotTaken, $this->targetStudent);


        // Process enlistment
        $this->processEnlistment($this->targetStudent, $dayPairings, $courseAvailability, $this->enlistment, $time_range, $remainingSub);
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

     private function processEnlistment($targetStudent, $dayPairings, $courses, $enlistment, $timeRangeMap, $remainingSub)
     {
         $maxUnits = $targetStudent->year_level_id < 4 ? 21 : PHP_INT_MAX; // Max units for 1st-3rd year, unlimited for 4th year
         $unitCount = 0;
         $enlistedSubjects = [];
         $flattenedTimeRange = $this->flattenArrayUnique($timeRangeMap);

         $subjectsAdded = true; // Flag to check if any subjects were added in the last iteration

         while ($subjectsAdded && (($targetStudent->year_level_id < 4 && $unitCount < $maxUnits) || ($targetStudent->year_level_id == 4 && $remainingSub > 0))) {

             $subjectsAdded = false; // Reset flag for each loop iteration

             foreach ($flattenedTimeRange as $time) {
                 foreach ($dayPairings as $dayPairing) { // iterate over M-Th, T-F, W-S
                     foreach ($courses as $subjectId) { // iterate over subjects
                         $subject = Subjects::where('subjectcode', $subjectId)->first();
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

                                         $subjectsAdded = true; // Indicate that a subject was added
                                     }
                                 }
                             }
                         }
                     }
                 }
             }
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
        $currentEnrolledCount = EnlistmentSubjects::where('caid', $availability->caid)->count();
        return $currentEnrolledCount < $availability->section_limit;
    }
}
