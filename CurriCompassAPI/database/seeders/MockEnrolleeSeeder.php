<?php

namespace Database\Seeders;

use App\Models\CurriculumSubjects;
use App\Models\SchoolYear;
use App\Models\StudentRecord;
use App\Models\SubjectsTaken;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MockEnrolleeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $roles = [3];
    private $grades = [1, 1.25, 1.5, 1.75, 2, 2.25, 2.5, 2.75];
    private $remarks = ["Excellent", "Very Good", "Very Good", "Good", "Good",  "Good", "Passing", "Passing"];
    private $students = [
        [
            'userfname' => "Darinel",
            'userlname' => "Gonzales",
            'usermiddle' => "Reynaldo",
            'contact_no' => "63686448748",
            'email' => "Gonzales@baliuagu.edu.ph",
            'password' => "Gonzales185",
            'student_no' => '2111082',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 1
        ],
        [
            'userfname' => "Ian",
            'userlname' => "Rivera",
            'usermiddle' => "Cruz",
            'contact_no' => "63287998767",
            'email' => "Rivera87@baliuagu.edu.ph",
            'password' => "Rivera542",
            'student_no' => '2111083',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 1
        ],
        [
            'userfname' => "Emily",
            'userlname' => "Johnson",
            'usermiddle' => "Grace",
            'contact_no' => "63833121099",
            'email' => "emily.grace.johnson@baliuagu.edu.ph",
            'password' => "Johnson#1942",
            'student_no' => '2111084',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 1
        ],
        [
            'userfname' => "Michael",
            'userlname' => "Thompson",
            'usermiddle' => "James",
            'contact_no' => "63752531950",
            'email' => "michael.james.thompson@baliuagu.edu.ph",
            'password' => "Thompson#6458",
            'student_no' => '2111085',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 1
        ],
        [
            'userfname' => "Sarah",
            'userlname' => "Davis",
            'usermiddle' => "Elizabeth",
            'contact_no' => "63751159911",
            'email' => "sarah.elizabeth.davis@baliuagu.edu.ph",
            'password' => "Davis*3049",
            'student_no' => '2111086',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 1
        ],
        [
            'userfname' => "Logan",
            'userlname' => "Harrison",
            'usermiddle' => "Matthew",
            'contact_no' => "638962973711",
            'email' => "loganharrison@baliuagu.edu.ph",
            'password' => "LogHarr2024",
            'student_no' => '2111041',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 2
        ],
        [
            'userfname' => "Penelope",
            'userlname' => "Dixon",
            'usermiddle' => "Grace",
            'contact_no' => "63623226534",
            'email' => "penelopedixon@baliuagu.edu.ph",
            'password' => "DixPen$123",
            'student_no' => '2111042',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 2
        ],
        [
            'userfname' => "Lucas",
            'userlname' => "Wright",
            'usermiddle' => "Andrew",
            'contact_no' => "63559330633",
            'email' => "lucaswright@baliuagu.edu.ph",
            'password' => "WrightLuc@",
            'student_no' => '2111043',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 2
        ],
        [
            'userfname' => "Riley",
            'userlname' => "Perez",
            'usermiddle' => "Elizabeth",
            'contact_no' => "63277703209",
            'email' => "rileyppperez@baliuagu.edu.ph",
            'password' => "PerezRiley",
            'student_no' => '2111044',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 2
        ],
        [
            'userfname' => "Benjamin",
            'userlname' => "Russell",
            'usermiddle' => "Daniel",
            'contact_no' => "63878505323",
            'email' => "benjaminrussell@baliuagu.edu.ph",
            'password' => "RussBen$$",
            'student_no' => '2111045',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 2
        ],
        [
            'userfname' => "Emily",
            'userlname' => "Santos",
            'usermiddle' => "Reyes",
            'contact_no' => "09123456789",
            'email' => "emilysantos@baliuagu.edu.ph",
            'password' => "Test",
            'student_no' => '2111001',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 3
        ],
        [
            'userfname' => "Michael",
            'userlname' => "Hernandez",
            'usermiddle' => "Cruz",
            'contact_no' => "09187654321",
            'email' => "michaelhernandez@baliuagu.edu.ph",
            'password' => "Test",
            'student_no' => '2111002',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 3
        ],
        [
            'userfname' => "Sophia",
            'userlname' => "Rivera",
            'usermiddle' => "Gomez",
            'contact_no' => "09134567890",
            'email' => "sophiarivera@baliuagu.edu.ph",
            'password' => "Test",
            'student_no' => '2111003',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 3
        ],
        [
            'userfname' => "Jacob",
            'userlname' => "Bautista",
            'usermiddle' => "Flores",
            'contact_no' => "09123456789",
            'email' => "jacobbautista@baliuagu.edu.ph",
            'password' => "Test",
            'student_no' => '2111004',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 3
        ],
        [
            'userfname' => "Isabella",
            'userlname' => "Ramirez",
            'usermiddle' => "Torres",
            'contact_no' => "09123456789",
            'email' => "isabellaramirez@baliuagu.edu.ph",
            'password' => "Test",
            'student_no' => '2111005',
            'status' => 'Irregular',
            'sy' => 4,
            'cid' => 3
        ],
    ];

    public function run(): void
    {
        foreach ($this->students as $student) {
            $user = User::create([
                'userfname' => $student['userfname'],
                'userlname' => $student['userlname'],
                'usermiddle' => $student['usermiddle'],
                'contact_no' => $student['contact_no'],
                'email' => $student['email'],
                'password' => Hash::make($student['password']),
            ]);

            // Assign roles to the user
            foreach ($this->roles as $role) {
                $user->user_roles()->attach($role);
            }

            // Get an array of subjectids for the specified curriculum id (cid)
            $subject_ids = CurriculumSubjects::where('cid', $student['cid'])->pluck('subjectid')->toArray();
            $subjectsTaken = rand(6, min(count($subject_ids), 58)); // Ensure it does not exceed available subjects
            $year_level = ($subjectsTaken >= 6 && $subjectsTaken <= 15) ? 1 : ($subjectsTaken > 15 && $subjectsTaken >= 27 ? 2 : ($subjectsTaken > 27 && $subjectsTaken >= 45 ? 3 : 4));
            // Create a student record
            $sys = SchoolYear::orderBy('sy', 'desc')->limit($student['sy'])->get();
            $sr = StudentRecord::create([
                'userid' => $user->userid,
                'year_level_id' => $year_level, // Random year level
                'status' => $student['status'],
                'student_no' => $student['student_no'],
                'cid' => $student['cid'],
                'sy' => $sys[0]['sy'], // Current school year
            ]);



            // Determine the number of subjects to take

            // Loop through and assign grades to the subjects
            for ($j = 0; $j < $subjectsTaken; $j++) {
                // Pick a random subject index
                $randomIndex = array_rand($subject_ids);
                // Get the subjectid
                $subject_id = $subject_ids[$randomIndex];
                // Remove the subjectid from the array
                array_splice($subject_ids, $randomIndex, 1);

                // Retrieve the subject record
                $subject = CurriculumSubjects::where('subjectid',$subject_id)->first();

                // Assign a random grade and remark
                $index = rand(0, 7);
                $sr->subjects_taken()->insert([
                    'subjectid' => $subject_id,
                    'taken_at' => $subject->semid == 1 ? "Sem 1" : ($subject->semid == 2 ? "Sem 2" : "Sem 3"),
                    'grade' => $this->grades[$index],
                    'remark' => $this->remarks[$index],
                    'srid' => $sr->srid,
                    'sy' => $sys[0]['sy'], // Current school year
                ]);
            }
        }
    }

}
