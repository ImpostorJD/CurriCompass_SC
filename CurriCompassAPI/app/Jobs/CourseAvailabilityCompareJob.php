<?php

namespace App\Jobs;

use App\Models\CourseAvailability;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * 06/09/24
 *
 * This is a job that will output true or false if the course is available.
 */
class CourseAvailabilityCompareJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courseId;
    /**
     * Create a new job instance.
     */
    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        return count(CourseAvailability::where('subjectid', $this->courseId)->get()) != 0;
    }
}
