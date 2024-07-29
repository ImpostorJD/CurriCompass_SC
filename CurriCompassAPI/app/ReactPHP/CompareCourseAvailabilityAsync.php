<?php
namespace App\ReactPHP;

use App\Models\CourseAvailability;
use App\Models\EnlistmentSubjects;
use App\Models\Subjects;
use App\Models\SubjectsTaken;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/../../vendor/autoload.php';

class CompareCourseAvailabilityAsync {

    public static function compareCourseAvailability($course_code, $targetStudent, $grade){
        //check if courese is available

        $courseAvailability = count(CourseAvailability::where('coursecode', $course_code)->get());

        //immediately return if course availability is not available
        if($courseAvailability == 0){
            return null;
        }

        //get course and check prerequisite
        $course = $targetStudent->curriculum->curriculum_subjects->firstWhere('coursecode', $course_code);
        if(!$course){
            return null;
        }

        //check if already was enlisted
        foreach($targetStudent->enlistment as $e){
            foreach($e->enlistment_subjects as $es){
                Log::info(json_encode($es));
                if($course_code == $es->coursecode && ($grade != "5" && $grade != "x" && $grade != "w")){
                    return null;
                }
            }
        }

        //check prerequisite
        $pre_req = $course->prerequisites != null && $course->prerequisites != "None" &&  $course->prerequisites != "NONE" ? explode(" & ", $course->prerequisites) : null;
        if($pre_req) {
            $yearLevelStanding = array_filter($pre_req, function($string) {
                return strpos($string, "STANDING") !== false;
            });

            if(count($yearLevelStanding) > 0){
                if($yearLevelStanding[0] < $targetStudent->year_level_id){
                    return null;
                }

            }else {
                $graduatingStanding = array_filter($pre_req, function($string) {
                    return strpos($string, "GRADUATING") !== false;
                });
                if(count($graduatingStanding) > 0){
                    $lastcoursetotake = $targetStudent->curriculum->curriculum_subject
                    ->orderBy('year_level_id', 'desc')
                    ->orderBy('semid', 'desc')->first();
                    if($lastcoursetotake->year_level_id < $targetStudent->year_level_id){
                        return null;
                    }
                }

            }

            $coursePrerequisite = array_filter($pre_req, function($string) {
                return strpos($string, "GRADUATING") === false && strpos($string, "STANDING") === false;
            });

            if(count($coursePrerequisite) > 0){
                foreach($coursePrerequisite as $pre_req){
                    $subject = SubjectsTaken::where('coursecode', $pre_req)
                        ->where('srid', $targetStudent->srid)
                        ->first();

                    //if subjectsTaken is null
                    if($subject == null){
                        return null;
                    }

                    //return if pre-requisites subjects are failed
                    if($subject->grade == "x" || $subject->grade == "w"|| $subject->grade == "5"){
                        return null;
                    }
                }
            }
        }
        return [$course_code => $grade];
    }
}

