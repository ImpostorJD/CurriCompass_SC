<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            [
                "programcode" => "BSIT",
                "programdesc" => "Bachelor of Science in Information Technology",
            ],
            [
                "programcode" => "BSCS",
                "programdesc" => "Bachelor of Science in Computer Science",
            ],
            // [
            //     "programcode" => "ACT",
            //     "programdesc" => "Associate in Computer Technology",
            // ],
        ]);
    }
}
