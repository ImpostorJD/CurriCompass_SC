<?php

namespace App\Jobs;

use App\Models\SubjectsTaken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IdentifyMissingCourseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courseid;
    protected $targetStudent;
    /**
     * Create a new job instance.
     */
    public function __construct($courseid, $targetStudent)
    {
        $this->courseid = $courseid;
        $this->targetStudent = $targetStudent;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $subject_taken = SubjectsTaken::where('srid', $this->targetStudent->srid)
            ->where('subjectid', $this->courseid)
            ->first();

        if($subject_taken == null){
            return $this->courseid;
        }

        if($subject_taken->remark == "Failed" || $subject_taken->remark == "Withdrawn"){
            return $this->courseid;
        }

        return null;
    }
}
