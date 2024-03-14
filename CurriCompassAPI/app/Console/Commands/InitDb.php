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
        echo("migrating databases... \n Please wait...");
        $this->call('migrate:fresh');
        echo("successfully migrated.");
        echo("Commencing database seeding:");
        $this->call('db:seed', ['--class' => 'RoleSeeder']);
        echo("Role Table successfully seeded.");
        $this->call('db:seed', ['--class' => 'SemesterSeeder']);
        echo("Semester Table successfully seeded.");
        $this->call('db:seed', ['--class' => 'ProgramSeeder']);
        echo("Program Table successfully seeded.");
    }
}
