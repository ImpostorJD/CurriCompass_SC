<?php

namespace App\ReactPHP;

use App\Models\CurriculumSubjects;

require __DIR__ . '/../../vendor/autoload.php';

class RemainingSubAsync {
    public static function getRemainingSubCount($courses, $targetStudent){
        $subCount = CurriculumSubjects::whereIn('subjectid', $courses)
            ->where('cid', $targetStudent->cid)->count();

        return $subCount;
    }
}
