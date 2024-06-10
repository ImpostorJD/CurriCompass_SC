<?php

namespace Database\Seeders;

use App\Models\CurriculumSubjects;
use App\Models\SchoolYear;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BPOMockData extends Seeder
{
    private $grades = [1, 1.25, 1.5, 1.75, 2, 2.25, 2.5, 2.75];
    private $remarks = ["Excellent", "Very Good", "Very Good", "Good", "Good",  "Good", "Passing", "Passing"];
    private $roles = [3];
    private $students = [

        [
            'userfname' => "Don",
            'userlname' => "Copeman",
            'usermiddle' => "Bryon",
            'contact_no' => "63488043596",
            'email' => "donbyron.copeman65@gmail.com",
            'password' => "Donbryon09",
            'student_no' => '2111087',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Elvis",
            'userlname' => "Grieves",
            'usermiddle' => "Tristan",
            'contact_no' => "63274894600",
            'email' => "elvistristangrieves@gmail.com",
            'password' => "ElvisGrieves@09",
            'student_no' => '2111088',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Sal",
            'userlname' => "Grieves",
            'usermiddle' => "Joaquin",
            'contact_no' => "63498057741",
            'email' => "sal.joaquingrieves@gmail.com",
            'password' => "salJoaquin#97",
            'student_no' => '2111089',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Manual",
            'userlname' => "Bohlsen",
            'usermiddle' => "Benny",
            'contact_no' => "63752368367",
            'email' => "manuel.bennybohlsen00@gmail.com",
            'password' => "Manuel@36",
            'student_no' => '2111090',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Israel",
            'userlname' => "Rim",
            'usermiddle' => "Joaquin",
            'contact_no' => "63813492733",
            'email' => "israeljoaquin.rim@gmail.com",
            'password' => "Joaquin67@Rim",
            'student_no' => '2111091',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "David",
            'userlname' => "Wilson",
            'usermiddle' => "Alexander",
            'contact_no' => "63325793577",
            'email' => "david.alexander.wilson@gmail.com",
            'password' => "Wilson%7610",
            'student_no' => '2111092',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Jessica",
            'userlname' => "Brown",
            'usermiddle' => "Marie",
            'contact_no' => "63265876479",
            'email' => "jessica.marie.brown@gmail.com",
            'password' => "Brown$4821",
            'student_no' => '2111093',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Christopher",
            'userlname' => "Moore",
            'usermiddle' => "John",
            'contact_no' => "63271273718",
            'email' => "christopher.john.moore@gmail.com",
            'password' => "Moore!5938",
            'student_no' => '2111094',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Amanda",
            'userlname' => "Taylor",
            'usermiddle' => "Lynn",
            'contact_no' => "63236238811",
            'email' => "amanda.lynn.taylor@gmail.com",
            'password' => "Taylor^1204",
            'student_no' => '2111095',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Joshua",
            'userlname' => "Anderson",
            'usermiddle' => "Daniel",
            'contact_no' => "63683851236",
            'email' => "joshua.daniel.anderson@gmail.com",
            'password' => "Anderson#3467",
            'student_no' => '2111096',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Megan",
            'userlname' => "Harris",
            'usermiddle' => "Louise",
            'contact_no' => "63242937205",
            'email' => "megan.louise.harris@gmail.com",
            'password' => "Harris@8501",
            'student_no' => '2111097',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Andrew",
            'userlname' => "Martinez",
            'usermiddle' => "Scott",
            'contact_no' => "63681226679",
            'email' => "andrew.scott.martinez@gmail.com",
            'password' => "Martinez*4732",
            'student_no' => '2111098',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Olivia",
            'userlname' => "Roberts",
            'usermiddle' => "Claire",
            'contact_no' => "63681226679",
            'email' => "olivia.claire.roberts@gmail.com",
            'password' => "Roberts%9023",
            'student_no' => '2111099',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Benjamin",
            'userlname' => "Stewart",
            'usermiddle' => "Paul",
            'contact_no' => "63380108781",
            'email' => "benjamin.paul.stewart@gmail.com",
            'password' => "Stewart$1586",
            'student_no' => '2111100',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Sophia",
            'userlname' => "Evans",
            'usermiddle' => "Rose",
            'contact_no' => "63525658080",
            'email' => "sophia.rose.evans@gmail.com",
            'password' => "Evans!6724",
            'student_no' => '2111101',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Mathew",
            'userlname' => "Hughes",
            'usermiddle' => "Ryan",
            'contact_no' => "63520307930",
            'email' => "matthew.ryan.hughes@gmail.com",
            'password' => "Hughes^2318",
            'student_no' => '2111102',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Abigail",
            'userlname' => "Adams",
            'usermiddle' => "Faith",
            'contact_no' => "63746691944",
            'email' => "abigail.faith.adams@gmail.com",
            'password' => "Adams#4937",
            'student_no' => '2111103',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Anthony",
            'userlname' => "Reed",
            'usermiddle' => "Joseph",
            'contact_no' => "63541832515",
            'email' => "anthony.joseph.reed@gmail.com",
            'password' => "Reed@7542",
            'student_no' => '2111104',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Chloe",
            'userlname' => "Turner",
            'usermiddle' => "Jane",
            'contact_no' => "63233043824",
            'email' => "chloe.jane.turner@gmail.com",
            'password' => "Turner*1820",
            'student_no' => '2111105',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Nicholas",
            'userlname' => "Walker",
            'usermiddle' => "Mark",
            'contact_no' => "63550861981",
            'email' => "nicholas.mark.walker@gmail.com",
            'password' => "Walker%3765",
            'student_no' => '2111106',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Natalie",
            'userlname' => "Rogers",
            'usermiddle' => "Anne",
            'contact_no' => "63554844702",
            'email' => "natalie.anne.rogers@gmail.com",
            'password' => "Rogers$5913",
            'student_no' => '2111107',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Samuel",
            'userlname' => "Perry",
            'usermiddle' => "George",
            'contact_no' => "63487965759",
            'email' => "samuel.george.perry@gmail.com",
            'password' => "Perry!8206",
            'student_no' => '2111108',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Emma",
            'userlname' => "Mitchell",
            'usermiddle' => "Katherine",
            'contact_no' => "63250109044",
            'email' => "emma.katherine.mitchell@gmail.com",
            'password' => "Mitchell^4739",
            'student_no' => '2111109',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Jacob",
            'userlname' => "Thompson",
            'usermiddle' => "William",
            'contact_no' => "63636102586",
            'email' => "jacob.william.thompson@gmail.com",
            'password' => "Thompson#6458",
            'student_no' => '2111110',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Madison",
            'userlname' => "Clark",
            'usermiddle' => "Nicole",
            'contact_no' => "63832900055",
            'email' => "madison.nicole.clark@gmail.com",
            'password' => "Clark@2390",
            'student_no' => '2111111',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Ethan",
            'userlname' => "Young",
            'usermiddle' => "Christopher",
            'contact_no' => "63642644518",
            'email' => "ethan.christopher.young@gmail.com",
            'password' => "Young*7183",
            'student_no' => '2111112',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Isabella",
            'userlname' => "Jenkins",
            'usermiddle' => "Grace",
            'contact_no' => "63829237313",
            'email' => "isabella.grace.jenkins@gmail.com",
            'password' => "Jenkins%5641",
            'student_no' => '2111113',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Antwan",
            'userlname' => "Murra",
            'usermiddle' => "Wilfredo",
            'contact_no' => "63545801896",
            'email' => "antwanwilfredomurra@gmail.com",
            'password' => "Antwanwilfredo@08",
            'student_no' => '2111114',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Leesa",
            'userlname' => "Lopara",
            'usermiddle' => "Corrina",
            'contact_no' => "63867715094",
            'email' => "leesa.corrinalopara35@gmail.com",
            'password' => "Leesa@corrina",
            'student_no' => '2111115',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Youlanda",
            'userlname' => "Fulginti",
            'usermiddle' => "Alexis",
            'contact_no' => "63813608446",
            'email' => "youlandaalexisfulginti@gmail.com",
            'password' => "Youlanda64#$",
            'student_no' => '2111116',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Filomena",
            'userlname' => "Miccio",
            'usermiddle' => "Rochel",
            'contact_no' => "63249601016",
            'email' => "filomena.rochelmiccio@gmail.com",
            'password' => "Filomena@$#@",
            'student_no' => '2111117',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Kathryne",
            'userlname' => "Maxon",
            'usermiddle' => "Corrina",
            'contact_no' => "63821650986",
            'email' => "kathryne.corrina.maxon@gmail.com",
            'password' => "Corrina@52",
            'student_no' => '2111118',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Melvia",
            'userlname' => "Limbach",
            'usermiddle' => "Alexis",
            'contact_no' => "63261897395",
            'email' => "melvia.alexislimbach89@gmail.com",
            'password' => "Alexislimbach12#@$",
            'student_no' => '2111119',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Emily",
            'userlname' => "Wilson",
            'usermiddle' => "Grace",
            'contact_no' => "63261897395",
            'email' => "emily.g.wilson@example.com",
            'password' => "XanderTh0mp@2024!",
            'student_no' => '2111120',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Alexander",
            'userlname' => "Thompson",
            'usermiddle' => "James",
            'contact_no' => "63261897395",
            'email' => "alexander.j.thompson@example.com",
            'password' => "Em!lyGr@ceW1ls0n!",
            'student_no' => '2111121',
            'status' => 'Regular',
            'year_level_id' => 4,
            'sy' => 1
        ]

    ];

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
                'cid' => 2,
                'sy' =>  $sys[$syindex]['sy'],
            ]);

            $curriculum_subjects = CurriculumSubjects::where('cid', 2)
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
