<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects') ->insert([
           [
                "subjectname" => "Android 1",
                "subjectcode" => "WAM 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 2,
                'subjectunitlec' => 1,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
            ],

            [
                "subjectname" => "Android 2",
                "subjectcode" => "WAM 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 2,
                'subjectunitlec' => 1,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
            ],
        ]);

        DB::table('course_availabilities')->insert([
           [
                'semid' => 1,
                'subjectid' => 1,
           ],
           [
                'semid' => 2,
                'subjectid' => 2,
           ],
        ]);

        DB::table('pre__requisites')->insert([
            [
                'subjectid' => 1,
                'year_level' => null,
                //'completion' => null
            ],

            [
                'subjectid' => 2,
                'year_level' => null,
                //'completion' => null
            ],
        ]);

        DB::table('pre__requisites__subjects')->insert([
            [
                'prid' => 2,
                'subjectid' => 1,
            ],
        ]);

    }
}
