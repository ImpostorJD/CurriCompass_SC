<?php

namespace App\ReactPHP;
require __DIR__ . '/../../vendor/autoload.php';

use App\Models\StudentRecord;
use App\Models\Subjects;
use App\Models\SubjectsTaken;


class PredictiveGradeAsync {
    public static function gradedWeightAverage($targetCourse, $referenceSimilarities){
        $summationSimilarity = 0;
        $summationCourseGwa = 0;
        // iterate through the matrix of reference similarity (assuming all are reference students)

        foreach($referenceSimilarities as $studentid => $similarity){
            $student = StudentRecord::where('student_no', $studentid)->first();
            $course = SubjectsTaken::where('srid', $student->srid)
                ->where('coursecode', $targetCourse)
                ->first();
            if($course == null){
                continue;
            }else{
                $courseGwa = floatval($course->grade);
                $summationCourseGwa += ($similarity * $courseGwa);
                $summationSimilarity += $similarity;
            }

        }

        if($summationSimilarity == 0){
            return [$targetCourse => 1];
        }
        //retrieve the predicted GWA
        return [$targetCourse => $summationCourseGwa / $summationSimilarity];

    }
}
