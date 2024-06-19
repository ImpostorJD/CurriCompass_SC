<?php

namespace App\Jobs;

use App\Models\SemSy;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkStudentAsInactive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $latest = null;
    /**
     * Create a new job instance.
     */
    public function __construct($latest)
    {
        $this->latest = $latest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $semsy = $this->latest;

        $students = User::whereHas('student_record', function($query) use ($semsy){
            $query->where('status', "!=", "Graduated")
                ->where('status', "!=", "Inactive");
            $query->whereDoesntHave('enlistment', function($query) use ($semsy){
                $query->where('semsyid', $semsy->semsyid);
            });

        })->get()->pluck('userid');

        StudentRecord::whereIn('userid', $students)->update([
            'status' => "Inactive",
        ]);

    }
}
