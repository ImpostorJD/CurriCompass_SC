<?php

namespace Database\Seeders;

use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\SemSy;
use App\Models\Subjects;
<<<<<<< Updated upstream
use Illuminate\Database\Console\Seeds\WithoutModelEvents
=======
use Illuminate\Database\Seeder;
>>>>>>> Stashed changes

class CourseAvailabilitySeeder extends Seeder
{
    private $time_range_lab = [
        '8-11', '11-2', '2-5',
    ];

    private $time_range_lec = [
        '8-10', '10-12', '1-3', '3-5'
    ];

    private $days_pairing = [
        'M-Th', 'T-F', 'W-S'
    ];

    public function run(): void
    {
        $curricula = Curriculum::all();
        $semSy = SemSy::orderBy('semsyid', 'desc')->first();

        foreach ($curricula as $curriculum) {
            $curriculum_subjects = CurriculumSubjects::where('cid', $curriculum->cid)->get();

            foreach ($curriculum_subjects as $csubject) {
                if ($csubject->semid == $semSy->semid){

                    $subject = Subjects::where('subjectid', $csubject->subjectid)->first();
                    $secLimit = $subject->subjecthourslab > $subject->subjecthourslec ? rand(26, 45) : 0;

                    // Initialize an array to keep track of used combinations for this subject
                    $usedCombinations = [];

                    $time_range = $subject->subjecthourslab > $subject->subjecthourslec ? $this->time_range_lab : $this->time_range_lec;

                    // Counter for time slots and day pairings
                    $timeCounter = 0;
                    $dayPairingCounter = 0;

                    // Systematically iterate over time slots and day pairings
                    for ($i = 0; $i < count($time_range) * count($this->days_pairing); $i++) {
                        $timeSlot = $time_range[$timeCounter];
                        $daysPairing = $this->days_pairing[$dayPairingCounter];
                        $combinationKey = $daysPairing . '-' . $timeSlot;

                        // Check if this specific availability already exists
                        $existingCourseAvailability = CourseAvailability::where('subjectid', $subject->subjectid)
                            ->where('days', $daysPairing)
                            ->where('time', $timeSlot)
                            ->first();

                        // Skip if this combination is already used
                        if (!in_array($combinationKey, $usedCombinations) && $existingCourseAvailability === null) {
                            // Create new CourseAvailability entry
                            CourseAvailability::create([
                                'subjectid' => $subject->subjectid,
                                'time' => $timeSlot,
                                'semsyid' => $semSy->semsyid,
                                'section' => "CITE - " . $csubject->year_level_id . "-year",
                                'section_limit' => $secLimit,
                                'days' => $daysPairing
                            ]);

                            // Mark this combination as used for this subject
                            $usedCombinations[] = $combinationKey;
                        }

                        // Update counters for the next iteration
                        $timeCounter = ($timeCounter + 1) % count($time_range);
                        if ($timeCounter === 0) {
                            $dayPairingCounter = ($dayPairingCounter + 1) % count($this->days_pairing);
                        }
                    }
                }
            }
<<<<<<< Updated upstream


=======
>>>>>>> Stashed changes
        }
}
