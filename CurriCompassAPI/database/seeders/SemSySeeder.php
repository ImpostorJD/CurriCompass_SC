<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use App\Models\SemSy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemSySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sy = SchoolYear::orderBy('sy', 'desc')->first();

        SemSy::create([
           'semid' => 2,
           'sy' => $sy->sy,
        ]);
    }
}
