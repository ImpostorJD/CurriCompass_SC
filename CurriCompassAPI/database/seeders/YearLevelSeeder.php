<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('year_levels')->insert([
           ['year_level_desc' => '1st Year'],
           ['year_level_desc' => '2nd Year'],
           ['year_level_desc' => '3rd Year'],
           ['year_level_desc' => '4th Year'],
        ]);
    }
}
