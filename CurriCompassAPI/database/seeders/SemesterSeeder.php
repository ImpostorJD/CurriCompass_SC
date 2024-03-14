<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('semesters')->insert([
            ["semdesc" => "1st Semester"],
            ["semdesc" => "2nd Semester"],
            ["semdesc" => "3rd Semester"],
        ]);
    }
}
