<?php
namespace App\ReactPHP;

use App\Models\CourseAvailability;
use App\Models\CurriculumSubjects;
use App\Models\Subjects;

require __DIR__ ."/../../vendor/autoload.php";

class SortByCurriculumSubjectAsync {
    public static function courseSorting($coursesId){

        return self::sortSubjectbyCurriculumSubject($coursesId);
    }

    private static function sortSubjectbyCurriculumSubject($subjectIds) {
        $sub = Subjects::whereIn('subjectcode', $subjectIds)
            ->get()
            ->pluck('subjectid')
            ->toArray();
        $csubjects = CurriculumSubjects::whereIn('subjectid', $sub)
            ->orderBy('year_level_id', 'asc')
            ->orderBy('semid', 'asc')
            ->get()
            ->pluck('subjectid')
            ->toArray();

        return Subjects::whereIn('subjectid', $csubjects)
                        // ->sortBySubjectHoursLab()
                        ->get()
                        ->pluck('subjectcode')
                        ->toArray();
    }
}
