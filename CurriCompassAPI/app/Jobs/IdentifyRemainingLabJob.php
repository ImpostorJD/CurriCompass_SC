<?php

namespace App\Jobs;

use App\Models\Subjects;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IdentifyRemainingLabJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courses;
    /**
     * Create a new job instance.
     */
    public function __construct($courses)
    {
        $this->courses = $courses;
    }


    /**
     * Execute the job.
     */
    public function handle()
    {

        $labCount = 0;
        foreach($this->courses as $courseId){
            $course = Subjects::where('subjectid', $courseId)->first();
            if($course->subjecthourslab > $course->subjecthourslec){
                $labCount++;
            }
        }

        return $labCount;
    }
}
