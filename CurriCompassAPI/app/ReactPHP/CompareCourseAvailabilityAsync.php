<?php
namespace App\ReactPHP;

use App\Models\CourseAvailability;
use App\Models\Subjects;
use App\Models\SubjectsTaken;

require __DIR__ . '/../../vendor/autoload.php';

class CompareCourseAvailabilityAsync {

    public static function compareCourseAvailability($course_code, $targetStudent){
        //check if courese is available
        $course = Subjects::where('subjectcode', $course_code)->first();

        $courseAvailability = count(CourseAvailability::where('subjectid', $course->subjectid)->get());

        //immediately return if course availability is not available
        if($courseAvailability == 0){
            return null;
        }
        //get course
        $course = Subjects::where('subjectid', $course->subjectid)
            ->with(['pre_requisites' => function ($query){
                $query->with('pre_requisites_subjects');
            }])->first();

        //check if pre-requisite has year standing
        if($course->pre_requisites->year_level_id != null &&
            $course->pre_requisites->year_level_id < $targetStudent->year_level_id){
                return null;
        }

        //check if pre-requisites subjects are taken
        foreach($course->pre_requisites->pre_requisites_subjects as $pre_req){
            $subject = SubjectsTaken::where('subjectid', $pre_req->subjectid)
                ->where('srid', $targetStudent->srid)
                ->first();

            //if subjectsTaken is null
            if($subject == null){
                return null;
            }

            //return if pre-requisites subjects are failed
            if($subject->remark == "Failed" || $subject->remark == "Withdrawn"){
                return null;
            }
        }

        return $course_code;
    }
}

