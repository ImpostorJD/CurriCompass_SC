<?php
namespace App\ReactPHP;

use App\Models\CourseAvailability;
use App\Models\Subjects;

require __DIR__ ."/../../vendor/autoload.php";

class SegregateCourseAsync {
    public static function courseClustering($coursesId){
        $firstPairing = [];
        $secondPairing = [];
        $thirdPairing = [];

        //iterates through the courses
        foreach($coursesId as $courseId){
            // retrieve availability of courses
            $courseAvailability = CourseAvailability::where('subjectid', $courseId)->get();

            foreach($courseAvailability as $c){

                if($c->days == "M-Th"){
                    $firstPairing[] = $c->subjectid;
                } else if($c->days == "T-F"){
                    $secondPairing[] = $c->subjectid;
                } else if($c->days == "W-S"){
                    $thirdPairing[] = $c->subjectid;
                }
            }

            $sortedSubjectIdsMTh = self::sortSubjectIdsByLabHours($firstPairing);
            $sortedSubjectIdsTF = self::sortSubjectIdsByLabHours($secondPairing);
            $sortedSubjectIdsWS = self::sortSubjectIdsByLabHours($thirdPairing);
        }

        return [$sortedSubjectIdsMTh, $sortedSubjectIdsTF, $sortedSubjectIdsWS];
    }

    private static function sortSubjectIdsByLabHours($subjectIds) {
        return Subjects::whereIn('subjectid', $subjectIds)
                        ->sortBySubjectHoursLab()
                        ->get()
                        ->pluck('subjectid')
                        ->toArray();
    }
}
