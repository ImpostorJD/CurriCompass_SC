<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-db';

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
        //
        echo("migrating databases... \n Please wait...\n");
        $this->call('migrate:fresh');
        echo("successfully migrated.\n");
        echo("Commencing database seeding:");
        $this->call('db:seed', ['--class' => 'RoleSeeder']);
        echo("Role Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'YearLevelSeeder']);
        echo("Year Level Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'UserSeeder']);
        echo("User Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'SchoolYearSeeder']);
        echo("School Year Table successfully seeded. \n");
        $this->call('db:seed', ['--class' => 'SemesterSeeder']);
        echo("Semester Table successfully seeded. \n");
    }
}
