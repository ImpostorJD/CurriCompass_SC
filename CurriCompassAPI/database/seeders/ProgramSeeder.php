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
                "programdesc" => "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY",
            ],
            [
                "programcode" => "BSCS",
                "programdesc" => "BACHELOR OF SCIENCE IN COMPUTER SCIENCE",
            ],
            [
                "programcode" => "ACT",
                "programdesc" => "ASSOCIATE IN COMPUTER TECHNOLOGY",
            ],
        ]);
    }
}
