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
           ["subjectname" => "Android 1",
            "subjectcode" => "WAM 2",
            "subjectcredits" => 3,
            "subjecttype" => "Lab"],

           ["subjectname" => "Android 2",
            "subjectcode" => "WAM 4",
            "subjectcredits" => 3,
            "subjecttype" => "Lab"],
        ]);

        DB::table('pre__requisites')->insert([
            [ 'subjectid' => 1,
            'year_level' => null,
            'completion' => null],

            [ 'subjectid' => 2,
            'year_level' => null,
            'completion' => null],
        ]);
        DB::table('pre__requisites__subjects')->insert([
            [
                'prid' => 2,
                'subjectid' => 1,
            ],
        ]);

    }
}
