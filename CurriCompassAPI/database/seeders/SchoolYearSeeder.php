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
                'sy_start' => Carbon::create('2020', '08', '23'),
                'sy_end' =>  Carbon::create('2021', '07', '21'),
            ],
            [
                'sy_start' => Carbon::create('2021', '08', '23'),
                'sy_end' =>  Carbon::create('2022', '07', '21'),
            ],
            [
                'sy_start' => Carbon::create('2022', '08', '23'),
                'sy_end' =>  Carbon::create('2023', '07', '21'),
            ],
            [
                'sy_start' => Carbon::create('2023', '08', '23'),
                'sy_end' =>  Carbon::create('2024', '07', '21'),
            ],
        ]);
    }
}
