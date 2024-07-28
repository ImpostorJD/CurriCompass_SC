<?php

namespace App\Imports;

use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\Programs;
use App\Models\SchoolYear;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CurriculumSheetImport implements ToCollection
{
    private $headers = [];
    private $secondaryheaders = [];

    private $subjects = [];
    private $program = null;
    private $specialization = null;
    private $curriculumYear = null;

    private $level = null;
    private $sem = null;

    private $metaInfo = [];
    private $headersFound = false;
    private $secondaryheadersFound = false;

    private $metaInfoLogged = false;
    private $reachedBottom = false;

    public function collection(Collection $rows)
    {
        $this->resetState();
        $previousRow = null;

        foreach ($rows as $key => $row) {
            $rowArray = $row->toArray();
            $rowString = implode(' ', $rowArray);

            // Ensure the row has exactly 9 columns
            if (count($rowArray) > 9) {
                $rowArray = array_slice($rowArray, 0, 9);
            } elseif (count($rowArray) < 9) {
                $rowArray = array_pad($rowArray, 9, ''); // Pad with empty strings
            }

            // Detect meta info based on content, across all columns
            if (!$this->metaInfoLogged) {
                foreach ($rowArray as $index => $value) {
                    if (strpos($value, 'BACHELOR OF SCIENCE') !== false) {
                        $string = $value;
                        $updatedString = str_replace("CURRICULUM", "", $string);
                        $updatedString = trim($updatedString);
                        $this->program = $updatedString;
                        $this->metaInfo[] = $value;
                    } else if (strpos($value, 'ASSOCIATE') !== false) {
                        $string = $value;
                        $updatedString = str_replace("CURRICULUM", "", $string);
                        $updatedString = trim($updatedString);
                        $this->program = $updatedString;
                        $this->metaInfo[] = $value;
                        $this->metaInfo[] = ""; //ADD SPECIALIZATION EMPTY COLUMN

                    } else if (strpos($value, 'SPECIALIZATION') !== false) {
                        $this->specialization = explode(':', $value)[1];
                        $this->metaInfo[] = $value;
                    } else if (strpos($value, 'Effective:') !== false) {
                        $this->curriculumYear =  explode(' ', $value)[2];
                        $this->metaInfo[] = $value;
                    }

                }

                // Check if all required meta information is logged
                if (count($this->metaInfo) >= 3) {
                    $this->metaInfoLogged = true;
                }
                continue;
            }

            // Check if headers are found
            if (in_array('GRADE', $rowArray)) {
                $this->headersFound = true;
                $this->headers = $rowArray;
                continue;
            }

            if (strpos($rowString, 'LEVEL') !== false) {
                $this->level = $rowArray[0];
                continue;
            }

            if (strpos($rowString, 'TRIMESTER') !== false) {
                $this->sem = $rowArray[0];
                continue;
            }

            // Skip rows until headers are found
            if (!$this->headersFound) {
                continue;
            }

            if (in_array('Lab', $rowArray)) {
                $this->secondaryheadersFound = true;
                $this->secondaryheaders = $rowArray;
                continue;
            }

            if (in_array('TOTAL', $rowArray)) {
                continue;
            }

            // Check if reached the bottom of the data
            if (strpos($rowString, 'Total units') !== false || strpos($rowString, 'Total Number of Units') !== false) {
                $this->reachedBottom = true;
                continue;
            }

            // Skip rows until secondary headers are found
            if (!$this->secondaryheadersFound) {
                continue;
            }

            if ($this->reachedBottom == true) {
                $this->logAndResetState();
                break;
            }

            // Handle merging descriptions when coursecode is NULL
            if ($rowArray[1] === null || $rowArray[1] === '') {
                // Merge description into the previous row and skip current row
                if ($previousRow) {
                    $previousRow[2] .= '' . $rowArray[2];
                }
                continue;
            }

            // Update the previous row
            if (!$this->reachedBottom) {
                $this->subjects[$this->level][$this->sem][] = $rowArray;
                $previousRow = &$this->subjects[$this->level][$this->sem][count($this->subjects[$this->level][$this->sem]) - 1];
            } else {
                $this->logAndResetState();
                break;
            }
        }

    }




    private function resetState()
    {
        $this->metaInfo = [];
        $this->headers = [];
        $this->secondaryheaders = [];
        $this->metaInfoLogged = false;
        $this->headersFound = false;
        $this->secondaryheadersFound = false;
        $this->reachedBottom = false;
        $this->subjects = [];
        $this->program = null;
        $this->specialization = null;
        $this->curriculumYear = null;
    }

    private function logAndResetState()
    {


        $_program = $this->program;

        // LOOK FOR PROGRAM AND SCHOOL YEAR
        $existingProgram = Programs::where('programdesc', $this->program)
            ->first();

        if (!$existingProgram) {
            $existingProgram = Programs::create([
                'programdesc' => $_program
            ]);
        }

        $schoolYears = explode('-', $this->curriculumYear);

        $existingYear = SchoolYear::where('sy_start', $schoolYears[0])
            ->where('sy_end', $schoolYears[1])->first();

        if (!$existingYear) {
            $existingYear = SchoolYear::create([
                'sy_start' => $schoolYears[0],
                'sy_end' => $schoolYears[1],
            ]);
        }

        // LOOK FOR EXISTING CURRICULUM
        $existingCurriculum = Curriculum::whereHas('program', function ($query) use ($existingProgram) {
            $query->where('programid', $existingProgram->programid);
        })->whereHas('school_year', function ($query) use ($existingYear) {
            $query->where('sy', $existingYear->sy);
        })->where('specialization', $this->specialization)->first();

        // SKIP IF EXISTING
        if ($existingCurriculum == null) {
            // CREATE CURRICULUM
            $curriculum = Curriculum::create([
                'programid' => $existingProgram->programid,
                'specialization' => $this->specialization, // Default value if none
                'sy' => $existingYear->sy,
            ]);
            // LOOP THROUGH SUBJECTS, ADD TO CURRICULUM
            foreach ($this->subjects as $level => $sem) {
                $currentYear = 1;
                switch ($level) {
                    case 'LEVEL I':
                        $currentYear = 1;
                        break;
                    case 'LEVEL II':
                        $currentYear = 2;
                        break;
                    case 'LEVEL III':
                        $currentYear = 3;
                        break;
                    case 'LEVEL IV':
                        $currentYear = 4;
                        break;
                    default:
                        break;
                }
                foreach ($sem as $s => $sub) {
                    $currentSem = 1;
                    switch ($s) {
                        case '1ST TRIMESTER':
                            $currentSem = 1;
                            break;
                        case '2ND TRIMESTER':
                            $currentSem = 2;
                            break;
                        case '3RD TRIMESTER':
                            $currentSem = 3;
                            break;
                        default:
                            break;
                    }

                    foreach ($sub as $subjects) {


                        CurriculumSubjects::create([
                            'cid' => $curriculum->cid,
                            'coursecode' => $subjects[1],
                            'coursedescription' => $subjects[2],
                            'units' => $subjects[3],
                            'unitslec' => $subjects[4],
                            'unitslab' => $subjects[5],
                            'hourslec' => $subjects[6],
                            'hourslab' => $subjects[7],
                            'prerequisites' => $subjects[8],
                            'semid' => $currentSem,
                            'year_level_id' => $currentYear,
                        ]);
                    }

                }
            }
            // END
        }
        $this->resetState();
    }

}
