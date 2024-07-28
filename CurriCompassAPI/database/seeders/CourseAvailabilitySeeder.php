<?php

namespace Database\Seeders;

use App\Models\CourseAvailability;
use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\SemSy;
use Illuminate\Database\Seeder;

class CourseAvailabilitySeeder extends Seeder
{

    private $time_range_lab = [
        '8-11', '11-2', '2-5',
    ];

    private $time_range_lec = [
        '8-10', '9-11', '10-12', '1-3', '2-4', '3-5'
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
                if ($csubject->semid == $semSy->semid) {

                    $secLimit = $csubject->hourslab > $csubject->hourslec ? 45 : 50;

                    // Initialize an array to keep track of used combinations for this subject
                    $usedCombinations = [];

                    $time_range = $csubject->hourslab > $csubject->hourslec ? $this->time_range_lab : $this->time_range_lec;

                    // Counter for time slots and day pairings
                    $timeCounter = 0;
                    $dayPairingCounter = 0;

                    // Systematically iterate over time slots and day pairings
                    while ($timeCounter < count($time_range) && $dayPairingCounter < count($this->days_pairing)) {
                        $timeSlot = $time_range[$timeCounter];
                        $daysPairing = $this->days_pairing[$dayPairingCounter];
                        $combinationKey = $daysPairing . '-' . $timeSlot;

                        // Check if this specific availability already exists
                        $existingCourseAvailability = CourseAvailability::where('coursecode', $csubject->coursecode)
                            ->where('days', $daysPairing)
                            ->where('time', $timeSlot)
                            ->first();

                        // Skip if this combination is already used
                        if (!in_array($combinationKey, $usedCombinations) && $existingCourseAvailability === null) {
                            // Create new CourseAvailability entry
                            CourseAvailability::create([
                                'coursecode' => $csubject->coursecode,
                                'time' => $timeSlot,
                                'semsyid' => $semSy->semsyid,
                                'section' => "CITE - " . $csubject->year_level_id,
                                'section_limit' => $secLimit,
                                'days' => $daysPairing,
                                'lab' => $csubject->hourslab > $csubject->hourslec,
                            ]);

                            // Mark this combination as used for this subject
                            $usedCombinations[] = $combinationKey;
                        }

                        // Update counters for the next iteration
                        $timeCounter++;
                        if ($timeCounter >= count($time_range)) {
                            $timeCounter = 0;
                            $dayPairingCounter++;
                        }
                    }
                }
            }
        }
    }
}
