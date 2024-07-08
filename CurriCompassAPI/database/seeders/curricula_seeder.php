<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class curricula_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('curricula')->insert([
            [
                "programid" => 1,
                "specialization" => "Web and Mobile Application Development",
                'sy' => 1,
            ],
            [
                "programid" => 1,
                "specialization" => "Service Management for BPO",
                'sy' => 1,
            ],
            [
                "programid" => 2,
                "specialization" => "Animation and Game Development",
                'sy' => 1,
            ],
            [
                "programid" => 1,
                "specialization" => "Network Administration and Security Management",
                'sy' => 1,
            ],
            [
                "programid" => 2,
                "specialization" => "Software Development",
                'sy' => 1,
            ],
            // [
            //     "programid" => 3,
            //     "specialization" => null,
            //     'sy' => 1,
            // ],
        ]);

        DB::table('curriculum_subjects')->insert([
            //BSCS AGD
            [
                'cid' => 3,
                'subjectid' => 1,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 2,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 3,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 4,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 5,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 6,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 7,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 8,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 9,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 10,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 11,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 12,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 13,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 14,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 15,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 16,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 17,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 18,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 19,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 20,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 3,
                'subjectid' => 21,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 22,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 23,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 24,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 25,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 26,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 27,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 28,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 29,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 30,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 31,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 32,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 33,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 35,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 36,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 37,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 38,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 39,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 40,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 41,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 42,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 3,
                'subjectid' => 43,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 44,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 45,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 46,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 47,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 48,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 49,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 50,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 51,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 52,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 76,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 53,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 54,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 77,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 55,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 78,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 79,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 80,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 56,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 57,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 58,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 3,
                'subjectid' => 59,
                'semid' => 1,
                'year_level_id' => 4
            ],
            [
                'cid' => 3,
                'subjectid' => 60,
                'semid' => 1,
                'year_level_id' => 4
            ],

            //BSIT WAM
            [
                'cid' => 1,
                'subjectid' => 1,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 2,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 3,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 4,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 5,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 6,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 7,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 8,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 9,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 10,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 11,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 12,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 13,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 14,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 15,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 16,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 17,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 18,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 19,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 20,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 1,
                'subjectid' => 21,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 22,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 23,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 24,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 25,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 26,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 27,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 28,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 29,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 30,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 31,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 32,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 33,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 35,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 43,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 37,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 36,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 45,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 41,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 40,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 42,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 1,
                'subjectid' => 34,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 61,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 62,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 44,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 48,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 47,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 49,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 63,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 64,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 66,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 65,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 68,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 53,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 77,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 70,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 67,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 69,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 56,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 57,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 58,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 1,
                'subjectid' => 59,
                'semid' => 1,
                'year_level_id' => 4
            ],
            [
                'cid' => 1,
                'subjectid' => 60,
                'semid' => 1,
                'year_level_id' => 4
            ],

            //BSIT BPO
            [
                'cid' => 2,
                'subjectid' => 1,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 2,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 3,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 4,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 5,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 6,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 7,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 8,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 9,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 10,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 11,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 12,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 13,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 14,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 15,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 16,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 17,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 18,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 19,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 20,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 2,
                'subjectid' => 21,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 22,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 23,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 24,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 25,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 26,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 27,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 28,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 29,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 30,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 31,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 32,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 33,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 35,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 43,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 37,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 36,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 45,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 41,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 40,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 42,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 2,
                'subjectid' => 71,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 72,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 62,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 44,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 48,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 47,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 49,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 73,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 74,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 66,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 65,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 68,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 53,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 77,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 75,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 67,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 69,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 56,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 57,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 58,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 2,
                'subjectid' => 59,
                'semid' => 1,
                'year_level_id' => 4
            ],
            [
                'cid' => 2,
                'subjectid' => 60,
                'semid' => 1,
                'year_level_id' => 4
            ],

            //BSIT NAS
            [
                'cid' => 4,
                'subjectid' => 1,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 2,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 3,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 4,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 5,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 6,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 7,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 8,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 9,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 10,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 11,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 12,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 13,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 14,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 15,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 16,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 17,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 18,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 19,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 20,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 4,
                'subjectid' => 21,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 22,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 23,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 24,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 25,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 26,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 27,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 28,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 29,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 30,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 31,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 32,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 33,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 35,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 43,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 37,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 36,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 45,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 41,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 40,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 42,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 4,
                'subjectid' => 86,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 87,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 62,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 44,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 48,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 47,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 49,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 88,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 89,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 66,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 65,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 68,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 53,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 77,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 90,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 67,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 69,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 56,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 57,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 58,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 4,
                'subjectid' => 59,
                'semid' => 1,
                'year_level_id' => 4
            ],
            [
                'cid' => 4,
                'subjectid' => 60,
                'semid' => 1,
                'year_level_id' => 4
            ],
            //BSCS SD
            [
                'cid' => 5,
                'subjectid' => 1,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 2,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 3,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 4,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 5,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 6,
                'semid' => 1,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 7,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 8,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 9,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 10,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 11,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 12,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 13,
                'semid' => 2,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 14,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 15,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 16,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 17,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 18,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 19,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 20,
                'semid' => 3,
                'year_level_id' => 1
            ],
            [
                'cid' => 5,
                'subjectid' => 21,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 22,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 23,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 24,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 25,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 26,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 27,
                'semid' => 1,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 28,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 29,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 30,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 31,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 32,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 33,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 35,
                'semid' => 2,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 36,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 37,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 34,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 39,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 40,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 41,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 42,
                'semid' => 3,
                'year_level_id' => 2
            ],
            [
                'cid' => 5,
                'subjectid' => 43,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 44,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 45,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 81,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 47,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 48,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 49,
                'semid' => 1,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 82,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 83,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 52,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 76,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 53,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 54,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 77,
                'semid' => 2,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 84,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 85,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 79,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 80,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 56,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 57,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 58,
                'semid' => 3,
                'year_level_id' => 3
            ],
            [
                'cid' => 5,
                'subjectid' => 59,
                'semid' => 1,
                'year_level_id' => 4
            ],
            [
                'cid' => 5,
                'subjectid' => 60,
                'semid' => 1,
                'year_level_id' => 4
            ],
        ]);
    }
}
