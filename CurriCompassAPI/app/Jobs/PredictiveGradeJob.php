<?php

namespace App\Jobs;

use App\Models\Subjects;
use App\Models\SubjectsTaken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * 06/09/24
 *
 * This is a job class that will be used to calculate the predicted GWA of the target student
 * based on the weighted mean average of the course computed using the classified neighbor students (seniors)
 */
class PredictiveGradeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targetCourse;
    protected $referenceSimilarities;

    /**
     * Create a new job instance.
     */
    public function __construct($targetCourse, $referenceSimilarities)
    {
        $this->targetCourse = $targetCourse;
        $this->referenceSimilarities = $referenceSimilarities;
    }

    /**
     * Execute the job.
     * Assumes that the reference similarity is key value pairs.
     */
    public function handle()
    {
        $summationSimilarity = 0;
        $summationCourseGwa = 0;
        // iterate through the matrix of reference similarity (assuming all are reference students)
        if (isset($this->referenceSimilarities)){
            foreach($this->referenceSimilarities as $studentid => $similarity){
                $courseGwa = SubjectsTaken::where('srid', $studentid)
                    ->where('subjectid', $this->targetCourse)
                    ->first()
                    ->grade;
                $summationCourseGwa += ($similarity * $courseGwa);
                $summationSimilarity += $similarity;
            }

            //retrieve the predicted GWA
            return $summationCourseGwa/$summationSimilarity;
        }

        return;
    }
}
