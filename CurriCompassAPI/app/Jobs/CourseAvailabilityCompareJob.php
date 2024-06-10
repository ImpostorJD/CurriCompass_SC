<?php

namespace App\Jobs;

use App\Models\CourseAvailability;
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
 * This is a job that will return course if it is available and pre-requisites are fulfilled.
 */
class CourseAvailabilityCompareJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courseId;
    protected $targetStudent;
    /**
     * Create a new job instance.
     */
    public function __construct($courseId, $targetStudent)
    {
        $this->courseId = $courseId;
        $this->targetStudent = $targetStudent;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //check if courese is available
        $courseAvailability = count(CourseAvailability::where('subjectid', $this->courseId)->get());

        //immediately return if course availability is not available
        if($courseAvailability  == 0){
            return null;
        }

        //get course
        $course = Subjects::where('subjectid', $this->courseId)
            ->with(['pre_requisites' => function ($query){
                $query->with('pre_requisites_subjects');
            }])->first();

        //check if pre-requisite has year standing
        if($course->pre_requisites->year_level_id != null &&
            $course->pre_requisites->year_level_id < $this->targetStudent->year_level_id){
                return null;
        }

        //check if pre-requisites subjects are taken
        foreach($course->pre_requisites->pre_requisites_subjects as $pre_req){
            $subject = SubjectsTaken::where('subjectid', $pre_req->subjectid)
                ->where('srid', $this->targetStudent->srid)
                ->first();

            //if subjectsTaken is null
            if($subject == null){
                return null;
            }

            //return if pre-requisites subjects are failed
            if($subject->remark == "Failed" || $subject->remark == "Withdrawn"){
                return null;
            }
        }

        return $this->courseId;
    }
}
