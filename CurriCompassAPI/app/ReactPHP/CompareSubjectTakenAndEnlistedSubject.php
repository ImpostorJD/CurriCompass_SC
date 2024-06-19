<?php
namespace App\ReactPHP;

use App\Models\SubjectsTaken;

require __DIR__ . '/../../vendor/autoload.php';

class CompareSubjectTakenAndEnlistedSubject {
    public static function compareAsync($student_record){
        $enlistment = $student_record['enlistment'][0];
        if($enlistment){
            foreach($enlistment['enlistment_subjects'] as $subjects) {
                $subject_taken = SubjectsTaken::where('subjectid', $subjects['course_availability']['subjectid'])
                    ->where('srid', $student_record['srid'])
                    ->first();
                if($subject_taken == null){
                    return [$student_record['student_no'] => false];
                }
            }

        }
        //iterate through enlisted subject
        //find if the subject is taken

        //if one subjecct enlisted is has no subject taken, return false
        return [$student_record['student_no'] => true];
    }
}
