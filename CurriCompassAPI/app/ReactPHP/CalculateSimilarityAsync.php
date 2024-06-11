<?php
namespace App\ReactPHP;

require __DIR__ . '/../../vendor/autoload.php';

use App\Models\SubjectsTaken;

class CalculateSimilarityAsync {

    public static function calculateSimilarity($targetStudent, $referenceStudent)
    {
        $similarity = 0;

        $targetCourses = SubjectsTaken::where('srid', $targetStudent['srid'])
                ->where('grade', '<=', 3)
                ->get()
                ->keyBy('subjectid');

            // Retrieve reference student's subjects taken and grades
            $referenceCourses = SubjectsTaken::where('srid', $referenceStudent['srid'])
                ->whereIn('subjectid', $targetCourses->keys())
                ->get()
                ->keyBy('subjectid');

            foreach ($targetCourses as $subjectId => $course) {
                if (isset($referenceCourses[$subjectId])) {
                    $referenceGrade = $referenceCourses[$subjectId]->grade;
                    $targetGrade = $course->grade;
                    $similarity += pow($referenceGrade - $targetGrade, 2);
                }
            }

            //get the square root of the summations of (courseGWA(reference) - courseGWA(target))^2 to get the euclidean distance
            $similarity = sqrt($similarity);

            //apply normalized euclidean similarity
            $similarity = 1/ (1 + $similarity);

        return [$referenceStudent->student_no => $similarity];
    }
}

