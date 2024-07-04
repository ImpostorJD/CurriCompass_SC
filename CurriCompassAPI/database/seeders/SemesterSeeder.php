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
            ["semdesc" => "1st Trimester"],
            ["semdesc" => "2nd Trimester"],
            ["semdesc" => "3rd Trimester"],
        ]);
    }
}
