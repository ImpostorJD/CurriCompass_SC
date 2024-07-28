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
    protected $signature = 'app:init-sample {--no-course-availability=false : Specify if course availability should be true or false} {--no-student-record=false : Specify if students should be true or false} {--no-program=false : Specify if no program should be generated} {--no-curriculum=false : Specify if no curriculum should be generated} {--no-school-year=false : Specify if no school year should be generated}';

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
        $no_course_availability = $this->option('no-course-availability') === 'true';
        $no_school_year = $this->option('no-school-year') === 'true';
        $no_student_record = $this->option('no-student-record') === 'true';
        $no_program = $this->option('no-program') === 'true';
        $no_curriculum = $this->option('no-curriculum') === 'true';

        echo("migrating databases... \n Please wait...\n");
        # drops all table and generate tables
        $this->call('migrate:fresh');
        echo("successfully migrated.\n");
        echo("Commencing database seeding:\n");

        $this->call('db:seed', ['--class' => 'RoleSeeder']);
        echo("Role Table successfully seeded.\n");

        $this->call('db:seed', ['--class' => 'UserSeeder']);
        echo("User Table successfully seeded. \n");

        $this->call('db:seed', ['--class' => 'SemesterSeeder']);
        echo("Semester Table successfully seeded.\n");

        $this->call('db:seed', ['--class' => 'YearLevelSeeder']);
        echo("Year Level Table successfully seeded. \n");

        # needs program to proceed
        if(!$no_program){
            $this->call('db:seed', ['--class' => 'ProgramSeeder']);
            echo("Program Table successfully seeded.\n");
        }

        #needs school year to proceed
        if(!$no_school_year){
            $this->call('db:seed', ['--class' => 'SchoolYearSeeder']);
            echo("School Year Table successfully seeded. \n");

            #needs school year to proceed
            $this->call('db:seed', ['--class' => 'SemSySeeder']);
            echo("Semester School Year Table successfully seeded. \n");
        }

        #needs curriculum, school year and program to proceed
        if(!$no_school_year && !$no_program && !$no_curriculum){
            $this->call('db:seed', ['--class' => 'curricula_seeder']);
            echo("Curricula Table successfully seeded.\n");
        }

        #needs school_year and course availability to proceed
        if(!$no_school_year && !$no_course_availability){
            $this->call('db:seed', ['--class' => 'CourseAvailabilitySeeder']);
            echo("Course Availability Table successfully seeded.\n");
        }

        #needs student record, school year, year level, curriculum, program to proceed
        // if(!$no_school_year && !$no_program && !$no_curriculum && !$no_student_record){
        //     echo("Seeding WAM Students Please Wait...\n");
        //     $this->call('db:seed', ['--class' => 'WebDevMockData']);

        //     echo("Student WAM specialization successfully seeded.\n");
        //     echo("Seeding AGD Students Please Wait...\n");

        //     $this->call('db:seed', ['--class' => 'AGDMockData']);
        //     echo("Student AGD specialization successfully seeded.\n");

        //     echo("Seeding BPO Students Please Wait...\n");
        //     $this->call('db:seed', ['--class' => 'BPOMockData']);

        //     echo("Student BPO specialization successfully seeded.\n");
        //     echo("Seeding Irregular Students Please Wait...\n");

        //     $this->call('db:seed', ['--class' => 'MockEnrolleeSeeder']);
        //     echo("Irregular Students successfully seeded.\n");
        // }
        echo("Ended seeding, thank you for waiting.\n");
    }
}
