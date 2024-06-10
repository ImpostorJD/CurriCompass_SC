<?php

namespace Database\Seeders;

use App\Models\CurriculumSubjects;
use App\Models\SchoolYear;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AGDMockData extends Seeder
{
    private $grades = [1, 1.25, 1.5, 1.75, 2, 2.25, 2.5, 2.75];
    private $remarks = ["Excellent", "Very Good", "Very Good", "Good", "Good",  "Good", "Passing", "Passing"];
    private $roles = [3];
    private $students = [

        [
            'userfname' => "Aria",
            'userlname' => "Griffin",
            'usermiddle' => "Sophia",
            'contact_no' => "638953500504",
            'email' => "ariasgriffin@email.com",
            'password' => "GriffinAria",
            'student_no' => '2111046',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Henry",
            'userlname' => "Martin",
            'usermiddle' => "Thomas",
            'contact_no' => "638956278937",
            'email' => "henrymartin@email.com",
            'password' => "HenryMar@1",
            'student_no' => '2111047',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Layla",
            'userlname' => "Hughes",
            'usermiddle' => "Olivia",
            'contact_no' => "63351043098",
            'email' => "laylahughes@email.com",
            'password' => "HughLayla2",
            'student_no' => '2111048',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Carter",
            'userlname' => "Morris",
            'usermiddle' => "Christopher",
            'contact_no' => "638172624277",
            'email' => "cartermorris@email.com",
            'password' => "",
            'student_no' => '2111049',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Nora",
            'userlname' => "Flores",
            'usermiddle' => "Amelia",
            'contact_no' => "63367012238",
            'email' => "noraflores@email.com",
            'password' => "FloresNora$",
            'student_no' => '2111050',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Samuel",
            'userlname' => "Bryant",
            'usermiddle' => "Michael",
            'contact_no' => "63867628941",
            'email' => "samuelbryant@email.com",
            'password' => "BryantSam7",
            'student_no' => '2111051',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Ellie",
            'userlname' => "Ramirez",
            'usermiddle' => "Grace",
            'contact_no' => "63492331323",
            'email' => "ellieramirez@email.com",
            'password' => "RamirezEll",
            'student_no' => '2111052',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Sebastian",
            'userlname' => "Rogers",
            'usermiddle' => "Christopher",
            'contact_no' => "63358396970",
            'email' => "sebastianrogers@email.com",
            'password' => "RogersSeb8",
            'student_no' => '2111053',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Mila",
            'userlname' => "Simmons",
            'usermiddle' => "Victoria",
            'contact_no' => "63498198475",
            'email' => "milavsimmons@email.com",
            'password' => "SimmMila$$",
            'student_no' => '2111054',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Joseph",
            'userlname' => "Patterson",
            'usermiddle' => "Daniel",
            'contact_no' => "63872621096",
            'email' => "josephpatterson@email.com",
            'password' => "PatterJoe@",
            'student_no' => '2111055',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Hazel",
            'userlname' => "Lawson",
            'usermiddle' => "Rose",
            'contact_no' => "63271325761",
            'email' => "hazellawson@email.com",
            'password' => "LawsonHaz$",
            'student_no' => '2111056',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "David",
            'userlname' => "Jenkins",
            'usermiddle' => "Michael",
            'contact_no' => "63438061247",
            'email' => "davidjenkins@email.com",
            'password' => "JenkinsDav",
            'student_no' => '2111057',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Eleanor",
            'userlname' => "Morrison",
            'usermiddle' => "Lily",
            'contact_no' => "638951537374",
            'email' => "eleanorvvmorrison@email.com",
            'password' => "MorElea@123",
            'student_no' => '2111058',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Owen",
            'userlname' => "West",
            'usermiddle' => "Michael",
            'contact_no' => "63643550117",
            'email' => "owenwest@email.com",
            'password' => "WestOwen$$",
            'student_no' => '2111059',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Savannah",
            'userlname' => "Schmidt",
            'usermiddle' => "Madison",
            'contact_no' => "63566746576",
            'email' => "savannahschmidt@email.com",
            'password' => "SchmidSav1",
            'student_no' => '2111060',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Jayden",
            'userlname' => "Hill",
            'usermiddle' => "Christopher",
            'contact_no' => "63855986874",
            'email' => "jaydennhill@email.com",
            'password' => "HillJayden",
            'student_no' => '2111061',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Aubree",
            'userlname' => "Perry",
            'usermiddle' => "Faith",
            'contact_no' => "63860999724",
            'email' => "aubreeperry@email.com",
            'password' => "PerryAubr@",
            'student_no' => '2111062',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Isaac",
            'userlname' => "Rice",
            'usermiddle' => "Benjamin",
            'contact_no' => "63850133804",
            'email' => "isaacrice@email.com",
            'password' => "RiceIsaac6",
            'student_no' => '2111063',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Kinsley",
            'userlname' => "Long",
            'usermiddle' => "Sophia",
            'contact_no' => "63273399305",
            'email' => "kinsleylong@email.com",
            'password' => "LongKins$$",
            'student_no' => '2111064',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Hudson",
            'userlname' => "Scott",
            'usermiddle' => "Andrew",
            'contact_no' => "638979775521",
            'email' => "hudsonscott@email.com",
            'password' => "ScottHuds0",
            'student_no' => '2111065',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Ariana",
            'userlname' => "Russell",
            'usermiddle' => "Grace",
            'contact_no' => "63832538782",
            'email' => "arianarussell@email.com",
            'password' => "RussAria@1",
            'student_no' => '2111066',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Levi",
            'userlname' => "Brooks",
            'usermiddle' => "Christopher",
            'contact_no' => "63456973299",
            'email' => "levibrooks@email.com",
            'password' => "BrooksLevi",
            'student_no' => '2111067',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Adalyn",
            'userlname' => "Taylor",
            'usermiddle' => "Isabella",
            'contact_no' => "63236398724",
            'email' => "adalyntaylor@email.com",
            'password' => "TaylorAda8",
            'student_no' => '2111068',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Daniel",
            'userlname' => "Wood",
            'usermiddle' => "Jacob",
            'contact_no' => "63244908401",
            'email' => "danielwood@email.com",
            'password' => "WoodDan@99",
            'student_no' => '2111069',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Adeline",
            'userlname' => "Clark",
            'usermiddle' => "Rose",
            'contact_no' => "63750685289",
            'email' => "adelineclark@email.com",
            'password' => "ClarkAde$$",
            'student_no' => '2111070',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Luke",
            'userlname' => "Morales",
            'usermiddle' => "Matthew",
            'contact_no' => "63868823037",
            'email' => "lukemorales@email.com",
            'password' => "MoraLuke10",
            'student_no' => '2111071',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Hailey",
            'userlname' => "Roberts",
            'usermiddle' => "Grace",
            'contact_no' => "63238556067",
            'email' => "haileyrroberts@email.com",
            'password' => "RobeHai@12",
            'student_no' => '2111072',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Grayson",
            'userlname' => "White",
            'usermiddle' => "Andrew",
            'contact_no' => "63744112369",
            'email' => "graysonwhite@email.com",
            'password' => "WhiteGray$",
            'student_no' => '2111073',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Isabella",
            'userlname' => "Thompson",
            'usermiddle' => "Amelia",
            'contact_no' => "63426999148",
            'email' => "itsabellathompson@email.com",
            'password' => "BellaThomp",
            'student_no' => '2111074',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Xavier",
            'userlname' => "Harris",
            'usermiddle' => "Michael",
            'contact_no' => "63541411467",
            'email' => "xavierharris@email.com",
            'password' => "HarrisXav7",
            'student_no' => '2111075',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Skylar",
            'userlname' => "Torres",
            'usermiddle' => "Lily",
            'contact_no' => "63844749284",
            'email' => "skylartorres@email.com",
            'password' => "TorrSky@88",
            'student_no' => '2111076',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Jack",
            'userlname' => "Stewart",
            'usermiddle' => "Andrew",
            'contact_no' => "63867210230",
            'email' => "jackstewart@email.com",
            'password' => "StewJack$9",
            'student_no' => '2111077',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Paisley",
            'userlname' => "Cox",
            'usermiddle' => "Madison",
            'contact_no' => "63235159565",
            'email' => "paisleycox@email.com",
            'password' => "CoxPais@10",
            'student_no' => '2111078',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Lincoln",
            'userlname' => "Foster",
            'usermiddle' => "Benjamin",
            'contact_no' => "63272177852",
            'email' => "lincolnfoster@email.com",
            'password' => "FosLin2024",
            'student_no' => '2111079',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Naomi",
            'userlname' => "Armstrong",
            'usermiddle' => "Grace",
            'contact_no' => "63832516464",
            'email' => "naaomiarmstrong@email.com",
            'password' => "ArmNaomi$$",
            'student_no' => '2111080',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ]

    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < count($this->students); $i++){
            $user = User::create([
                'userfname' => $this->students[$i]['userfname'],
                'userlname' => $this->students[$i]['userlname'],
                'usermiddle' => $this->students[$i]['usermiddle'],
                'contact_no' => $this->students[$i]['contact_no'],
                'email' => $this->students[$i]['email'],
                'password' => Hash::make($this->students[$i]['password']),
            ]);

            foreach($this->roles as $role) {
                $user->user_roles()->attach($role);
            }

            $sys = SchoolYear::orderBy('sy','desc')->limit($this->students[$i]['year_level_id'])->get();
            $syindex = 0;
            $currentYear = 1;

            $sr = StudentRecord::create([
                'userid' => $user->userid,
                'year_level_id' => $this->students[$i]['year_level_id'],
                'status' => $this->students[$i]['status'],
                'student_no' => $this->students[$i]['student_no'],
                'cid' => 3,
                'sy' =>  $sys[$syindex]['sy'],
            ]);

            $curriculum_subjects = CurriculumSubjects::where('cid', 3)
                ->where('year_level_id', '<=',$this->students[$i]['year_level_id'])
                ->get();

            // $sys = SchoolYear::orderBy('sy','desc')->limit($this->students[$i]['year_level_id'])->get();
            // $syindex = 0;
            // $currentYear = 1;

            if($currentYear < $this->students[$i]['year_level_id']){
                $syindex = count($sys) - 1;
            }

            //loop through curriculum records
            for($j = 0; $j < count($curriculum_subjects); $j++){

                //increment if current curriculum subject changed year level id
                if($currentYear != $curriculum_subjects[$j]['year_level_id']){
                    $currentYear = $curriculum_subjects[$j]['year_level_id'];
                    $syindex -= 1;
                }

                $index = rand(0, 7);
                $sr->subjects_taken()->insert([
                    'subjectid' => $curriculum_subjects[$j]['subjectid'],
                    'taken_at' => $curriculum_subjects[$j]['semid'] == 1 ? "Sem 1" :( $curriculum_subjects[$j]['semid'] == 2 ? "Sem 2" : "Sem 3"),
                    'grade' => $this->grades[$index],
                    'remark' => $this->remarks[$index],
                    'srid' => $user->student_record->srid,
                    'sy' => $sys[$syindex]['sy'],
                ]);
            }
        }

    }
}
