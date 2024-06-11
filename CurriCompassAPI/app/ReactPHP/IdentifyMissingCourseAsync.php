<?php
namespace App\ReactPHP;

require __DIR__ . '/../../vendor/autoload.php';

use App\Models\SubjectsTaken;

class IdentifyMissingCourseAsync {
    public static function identifyMissingCourse($courseid, $targetStudent){
        $subject_taken = SubjectsTaken::where('srid', $targetStudent->srid)
            ->where('subjectid', $courseid)
            ->first();

        if($subject_taken == null){
            return $courseid;
        }

        if($subject_taken->remark == "Failed" || $subject_taken->remark == "Withdrawn"){
            return $courseid;
        }

        return null;
    }
}
