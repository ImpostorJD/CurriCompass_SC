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
                "specialization" => null,
            ],
            [
                "programid" => 1,
                "specialization" => null,
            ],
        ]);
    }
}
