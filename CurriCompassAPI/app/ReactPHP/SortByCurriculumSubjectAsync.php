<?php
namespace App\ReactPHP;

use App\Models\CurriculumSubjects;
use App\Models\Subjects;

class SortByCurriculumSubjectAsync {
    public static function courseSorting($coursesWithGwa){
        return self::sortSubjectbyCurriculumSubject($coursesWithGwa);
    }

    private static function sortSubjectbyCurriculumSubject($coursesWithGwa) {
        // Extract course codes
        $courseCodes = array_keys($coursesWithGwa);

        // Retrieve subject ids based on course codes
        $subjects = Subjects::whereIn('subjectcode', $courseCodes)
            ->get()
            ->keyBy('subjectcode'); // Preserve subject code as key

        // Retrieve and sort curriculum subjects
        $curriculumSubjects = CurriculumSubjects::whereIn('subjectid', $subjects->pluck('subjectid'))
            ->orderBy('year_level_id', 'asc')
            ->orderBy('semid', 'asc')
            ->get();

        // Map subject IDs to their codes
        $subjectIdToCode = $subjects->pluck('subjectcode', 'subjectid');

        // Create a combined array to hold curriculum order and GWA
        $combinedCourses = [];
        foreach ($curriculumSubjects as $curriculumSubject) {
            $subjectId = $curriculumSubject->subjectid;
            $subjectCode = $subjectIdToCode[$subjectId] ?? null;
            if ($subjectCode && isset($coursesWithGwa[$subjectCode])) {
                $combinedCourses[] = [
                    'subjectcode' => $subjectCode,
                    'gwa' => $coursesWithGwa[$subjectCode],
                    'year_level_id' => $curriculumSubject->year_level_id,
                    'semid' => $curriculumSubject->semid
                ];
            }
        }

        // Sort by curriculum order and GWA
        usort($combinedCourses, function ($a, $b) {
            if ($a['year_level_id'] == $b['year_level_id']) {
                if ($a['semid'] == $b['semid']) {
                    return $a['gwa'] <=> $b['gwa'];
                }
                return $a['semid'] <=> $b['semid'];
            }
            return $a['year_level_id'] <=> $b['year_level_id'];
        });

        // Extract the sorted course codes with GWA
        $sortedCoursesWithGwa = [];
        foreach ($combinedCourses as $course) {
            // $sortedCoursesWithGwa[$course['subjectcode']] = $course['gwa'];
            $sortedCoursesWithGwa[] = $course['subjectcode'];
        }

        return $sortedCoursesWithGwa;
    }
}
