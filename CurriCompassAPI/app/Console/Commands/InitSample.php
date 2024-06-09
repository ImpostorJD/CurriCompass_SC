<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitSample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-sample';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo("migrating databases... \n Please wait...\n");
        $this->call('migrate:fresh');
        echo("successfully migrated.\n");
        echo("Commencing database seeding:\n");
        $this->call('db:seed', ['--class' => 'RoleSeeder']);
        echo("Role Table successfully seeded.\n");
        $this->call('db:seed', ['--class' => 'UserSeeder']);
        echo("User Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'YearLevelSeeder']);
        echo("Year Level Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'SemesterSeeder']);
        echo("Semester Table successfully seeded.\n");
        $this->call('db:seed', ['--class' => 'ProgramSeeder']);
        echo("Program Table successfully seeded.\n");
        $this->call('db:seed', ['--class' => 'SchoolYearSeeder']);
        echo("School Year Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'CourseSeeder']);
        echo("Course/Subjects Table successfully seeded.\n");
        $this->call('db:seed', ['--class' => 'curricula_seeder']);
        echo("Curricula Table successfully seeded.\n");
        echo("Seeding WAM Students Please Wait...\n");
        $this->call('db:seed', ['--class' => 'WebDevMockData']);
        echo("Student WAM specialization successfully seeded.\n");
        echo("Seeding AGD Students Please Wait...\n");
        $this->call('db:seed', ['--class' => 'AGDMockData']);
        echo("Student AGD specialization successfully seeded.\n");
        echo("Seeding BPO Students Please Wait...\n");
        $this->call('db:seed', ['--class' => 'BPOMockData']);
        echo("Student BPO specialization successfully seeded.\n");
    }
}
