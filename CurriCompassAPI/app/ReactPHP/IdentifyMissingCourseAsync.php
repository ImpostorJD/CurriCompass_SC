<?php
namespace App\ReactPHP;

require __DIR__ . '/../../vendor/autoload.php';

use App\Models\SubjectsTaken;

class IdentifyMissingCourseAsync {
    public static function identifyMissingCourse($courseid, $targetStudent){
        $subject_taken = SubjectsTaken::where('srid', $targetStudent->srid)
            ->where('coursecode', $courseid)
            ->first();

        if($subject_taken == null){
            return $courseid;
        }

        if($subject_taken->grade == "w" || $subject_taken->remark == "x" || $subject_taken->remark == "5"){
            return $courseid;
        }

        return null;
    }
}
