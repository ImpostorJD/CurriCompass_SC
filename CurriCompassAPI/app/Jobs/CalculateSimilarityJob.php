<?php

namespace App\Jobs;

use App\Models\Subjects;
use App\Models\SubjectsTaken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\Type\Integer;

/**
 * 06/09/24
 *
 * This is a job that will be executed to compare target student to neighbor student
 * using euclidean distance formula
 */
class CalculateSimilarityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targetStudent;
    protected $referenceStudent;
    /**
     * Create a new job instance.
     */
    public function __construct($target, $reference)
    {
        $this->targetStudent = $target;
        $this->referenceStudent = $reference;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //initialize similarity index as 0
        $similarity = 0;

        //retrieve user course subject taken (assumption is that they have similar curriculum)
        // $courses = SubjectsTaken::where('srid', $this->targetStudent['srid'])
        //     ->where('grade', '<=', 3)
        //     ->get();

        $targetCourses = SubjectsTaken::where('srid', $this->targetStudent['srid'])
            ->where('grade', '<=', 3)
            ->get()
            ->keyBy('subjectid');

        // Retrieve reference student's subjects taken and grades
        $referenceCourses = SubjectsTaken::where('srid', $this->referenceStudent['srid'])
            ->whereIn('subjectid', $targetCourses->keys())
            ->get()
            ->keyBy('subjectid');

        //iterate through subjects taken to get the summation of (courseGWA(reference) - courseGWA(target))^2
        // foreach($courses as $course){
        //     $similarity += pow(
        //         SubjectsTaken::where('srid', $this->referenceStudent['srid'])
        //             ->where('subjectid', $course->subjectid)->first()->grade
        //             - $course->grade, 2);
        // }
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
        return [$this->referenceStudent->student_no => $similarity];
    }
}
