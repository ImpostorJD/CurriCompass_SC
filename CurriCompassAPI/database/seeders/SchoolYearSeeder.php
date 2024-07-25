<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_years')->insert([
            [
                'sy_start' => 2020,
                'sy_end' =>  2021,
            ],
            [
                'sy_start' => 2021,
                'sy_end' =>  2022,
            ],
            [
                'sy_start' => 2022,
                'sy_end' =>  2023,
            ],
            [
                'sy_start' => 2023,
                'sy_end' =>  2024,
            ],
        ]);
    }
}
