<?php

namespace App\ReactPHP;

use App\Models\Subjects;

require __DIR__ . '/../../vendor/autoload.php';

class RemainingLabAsync {
    public static function getRemainingLab($courses){
        $labCount = 0;
        foreach($courses as $courseId){
            $course = Subjects::where('subjectid', $courseId)->first();
            if($course->subjecthourslab > $course->subjecthourslec){
                $labCount++;
            }
        }

        return $labCount;
    }
}
