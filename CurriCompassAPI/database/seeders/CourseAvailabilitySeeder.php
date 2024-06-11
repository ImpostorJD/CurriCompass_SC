<?php

namespace Database\Seeders;

use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\SemSy;
use App\Models\Subjects;
use Illuminate\Database\Console\Seeds\WithoutModelEvents

class CourseAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $time_range_lab = [
        '8-11', '11-2', '2-5',
    ];

    private $time_range_lec = [
        '8-10',
        '10-12',
        '1-3',
        '3-5'
    ];
    private $days_pairing = [
        'M-Th',
        'T-F',
        'W-S'
    ];

    public function run(): void
    {
        $curricula = Curriculum::all();

        $semSy = SemSy::orderBy('semsyid', 'desc')->first();

        foreach ($curricula as $curriculum){
            $curriculum_subjects = CurriculumSubjects::where('cid', $curriculum->cid)
                ->get();

            $numOfSec = rand(1, 2);
            foreach($curriculum_subjects as $csubject){
                $subject = Subjects::where('subjectid', $csubject->subjectid)->first();
                $secLimit = $subject->subjecthourslab > $subject->subjecthourslec ? rand(26, 45) : 0;
                for($i = 0; $i < $numOfSec; $i++){
                    $daysPairing = $this->days_pairing[rand(0, count($this->days_pairing) - 1)];
                    $timeSlot = $subject->subjecthourslab > $subject->subjecthourslec ? $this->time_range_lab[rand(0, count($this->time_range_lab) - 1)] : $this->time_range_lec[rand(0, count($this->time_range_lec) - 1)];

                    $existingCourseAvailability = CourseAvailability::where('subjectid', $subject->subjectid)
                        ->where('days', $daysPairing)
                        ->where('time', $timeSlot)
                        ->first();

                    if($existingCourseAvailability == null) {
                        CourseAvailability::create([
                            'subjectid' => $subject->subjectid,
                            'time' => $timeSlot,
                            'semsyid' => $semSy->semsyid,
                            'section' => "CITE" . ($i + 1),
                            'section_limit' => $secLimit,
                            'days' => $daysPairing
                        ]);
                    }
                }
                // if ($csubject->semid == $semSy->semid){

                // }

            }


        }
}
