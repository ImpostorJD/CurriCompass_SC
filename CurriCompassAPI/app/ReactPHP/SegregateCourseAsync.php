<?php
namespace App\ReactPHP;

use App\Models\CourseAvailability;
use App\Models\CurriculumSubjects;
use App\Models\Subjects;

require __DIR__ ."/../../vendor/autoload.php";

class SegregateCourseAsync {
    public static function courseClustering($coursesId){
        $firstPairing = [];
        $secondPairing = [];
        $thirdPairing = [];


        //iterates through the courses
        foreach($coursesId as $coursecode){

            $subject = Subjects::where('subjectcode', $coursecode)->first();

            // retrieve availability of courses
            $courseAvailability = CourseAvailability::where('subjectid', $subject->subjectid)->get();

            foreach($courseAvailability as $c){

                if($c->days == "M-Th"){
                    $firstPairing[] = $c->subjectid;
                } else if($c->days == "T-F"){
                    $secondPairing[] = $c->subjectid;
                } else if($c->days == "W-S"){
                    $thirdPairing[] = $c->subjectid;
                }
            }

            $sortedSubjectIdsMTh = self::sortSubjectbyCurriculumSubject($firstPairing);
            $sortedSubjectIdsTF = self::sortSubjectbyCurriculumSubject($secondPairing);
            $sortedSubjectIdsWS = self::sortSubjectbyCurriculumSubject($thirdPairing);
        }

        return [$sortedSubjectIdsMTh, $sortedSubjectIdsTF, $sortedSubjectIdsWS];
    }

    private static function sortSubjectbyCurriculumSubject($subjectIds) {
        $csubjects = CurriculumSubjects::whereIn('subjectid', $subjectIds)
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
