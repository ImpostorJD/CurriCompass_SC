<?php

namespace App\Jobs;

use App\Models\CourseAvailability;
use App\Models\Subjects;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * 06/09/24
 *
 * This is a job that will segregate courses based on day availability.
 * It will return three array, consisting of ids of courses
 * the days are: M-Th, T-F, W-S
 */
class CourseClusteringJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $coursesId;

    /**
     * Create a new job instance.
     * Assuming that the coursesId given has no unavailabile courses
     */
    public function __construct($coursesId)
    {
        $this->coursesId = $coursesId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $firstPairing = [];
        $secondPairing = [];
        $thirdPairing = [];

        //iterates through the courses
        foreach($this->coursesId as $courseId){
            // retrieve availability of courses
            $courseAvailability = CourseAvailability::where('subjectid', $courseId)->get();
            foreach($courseAvailability as $c){
                if($c->days == "M-Th"){
                    $firstPairing[] = $c->subjectId;
                } elseif($c->days == "T-F"){
                    $secondPairing[] = $c->subjectId;
                } elseif($c->days == "W-S"){
                    $thirdPairing[] = $c->subjectId;
                }
            }

            $sortedSubjectIdsMTh = $this->sortSubjectIdsByLabHours($firstPairing);
            $sortedSubjectIdsTF = $this->sortSubjectIdsByLabHours($secondPairing);
            $sortedSubjectIdsWS = $this->sortSubjectIdsByLabHours($thirdPairing);
        }

        return [$sortedSubjectIdsMTh, $sortedSubjectIdsTF, $sortedSubjectIdsWS];
    }

    private function sortSubjectIdsByLabHours($subjectIds) {
        return Subjects::whereIn('subjectid', $subjectIds)
                        ->sortBySubjectHoursLab()
                        ->get()
                        ->pluck('subjectid')
                        ->toArray();
    }
}
