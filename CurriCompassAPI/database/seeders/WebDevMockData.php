<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\SchoolYear;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WebDevMockData extends Seeder
{
    /**
     * Run the database seeds.
     */

    private $grades = [1, 1.25, 1.5, 1.75, 2, 2.25, 2.5, 2.75];
    private $remarks = ["Excellent", "Very Good", "Very Good", "Good", "Good",  "Good", "Passing", "Passing"];
    private $roles = [3];
    private $students = [
        //1st Years

        [
            'userfname' => "Ethan",
            'userlname' => "Mendoza",
            'usermiddle' => "Diaz",
            'contact_no' => "09123456789",
            'email' => "ethanmendoza@gmail.com",
            'password' => "Test",
            'student_no' => '2111006',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Olivia",
            'userlname' => "Perez",
            'usermiddle' => "Fernandez",
            'contact_no' => "09123456789",
            'email' => "oliviaferndanez@gmail.com",
            'password' => "Test",
            'student_no' => '2111007',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Lucas",
            'userlname' => "Aguilar",
            'usermiddle' => "Castillo",
            'contact_no' => "09123456789",
            'email' => "lucasaguilar@gmail.com",
            'password' => "Test",
            'student_no' => '2111008',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Ava",
            'userlname' => "Morales",
            'usermiddle' => "Vargas",
            'contact_no' => "09123456789",
            'email' => "avamorales@gmail.com",
            'password' => "Test",
            'student_no' => '2111009',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],
        [
            'userfname' => "Benjamin",
            'userlname' => "lopez",
            'usermiddle' => "Garcia",
            'contact_no' => "09123456789",
            'email' => "benjaminlopez@gmail.com",
            'password' => "Test",
            'student_no' => '2111010',
            'status' => 'Regular',
            'year_level_id' => 1,
            'sy' => 4
        ],

        //Second Years
        [
            'userfname' => "Mia",
            'userlname' => "Ortiz",
            'usermiddle' => "Santiago",
            'contact_no' => "09123456789",
            'email' => "miaortiz@gmail.com",
            'password' => "Test",
            'student_no' => '2111011',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Noah",
            'userlname' => "Castillo",
            'usermiddle' => "Ramos",
            'contact_no' => "09123456789",
            'email' => "noahramos@gmail.com",
            'password' => "Test",
            'student_no' => '2111012',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Amelia",
            'userlname' => "Silva",
            'usermiddle' => "Gonzales",
            'contact_no' => "09123456789",
            'email' => "ameliagonzales@gmail.com",
            'password' => "Test",
            'student_no' => '2111013',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Liam",
            'userlname' => "Alvarez",
            'usermiddle' => "Ramos",
            'contact_no' => "09123456789",
            'email' => "liamramos@gmail.com",
            'password' => "Test",
            'student_no' => '2111014',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Charlotte",
            'userlname' => "Reyes",
            'usermiddle' => "Morales",
            'contact_no' => "09123456789",
            'email' => "charlottemorales@gmail.com",
            'password' => "Test",
            'student_no' => '2111015',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Elijah",
            'userlname' => "Cruz",
            'usermiddle' => "Sanchez",
            'contact_no' => "09123456789",
            'email' => "elijahsanches@gmail.com",
            'password' => "Test",
            'student_no' => '2111016',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Harper",
            'userlname' => "Chaves",
            'usermiddle' => "Aquino",
            'contact_no' => "09123456789",
            'email' => " harperchaves@gmail.com",
            'password' => "Test",
            'student_no' => '2111017',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "William",
            'userlname' => "Reyes",
            'usermiddle' => "Navarro",
            'contact_no' => "09123456789",
            'email' => "williamreyes@gmail.com",
            'password' => "Test",
            'student_no' => '2111018',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Evelyn",
            'userlname' => "Diaz",
            'usermiddle' => "Bautista",
            'contact_no' => "09123456789",
            'email' => "evelynbautista@gmail.com",
            'password' => "Test",
            'student_no' => '2111019',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Jameson",
            'userlname' => "Garcia",
            'usermiddle' => "Cruz",
            'contact_no' => "09123456789",
            'email' => "jamesongarcia@gmail.com",
            'password' => "Test",
            'student_no' => '2111020',
            'status' => 'Regular',
            'year_level_id' => 2,
            'sy' => 3
        ],
        [
            'userfname' => "Scarlett",
            'userlname' => "Mendoza",
            'usermiddle' => "Castillo",
            'contact_no' => " 9112345679",
            'email' => "scarlettcastillo@gmail.com",
            'password' => "Dreamer2Whisper#Sunshine",
            'student_no' => '2111021',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Henry",
            'userlname' => "Gomez",
            'usermiddle' => "Morales",
            'contact_no' => " 9123456781",
            'email' => "henrymorales@gmail.com",
            'password' => "Nomad7Tranquil_Journey",
            'student_no' => '2111022',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Lillian",
            'userlname' => "Ortiz",
            'usermiddle' => "Vargas",
            'contact_no' => " 9134567892",
            'email' => "lillianvargas@gmail.com",
            'password' => "TwilightBlossom4Wish",
            'student_no' => '2111023',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Mason",
            'userlname' => "Reyes",
            'usermiddle' => "Santiago",
            'contact_no' => " 9145678903",
            'email' => "masonsantiago@gmail.com",
            'password' => "Essence6Breeze*Whisper",
            'student_no' => '2111024',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Zoe",
            'userlname' => "Fernandez",
            'usermiddle' => "Alvarez",
            'contact_no' => " 9156789014",
            'email' => "zoealvarez@gmail.com",
            'password' => "Starlight*Dewdrop9Meadow",
            'student_no' => '2111025',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Alexander",
            'userlname' => "Ramirez",
            'usermiddle' => "Silva",
            'contact_no' => " 9167890125",
            'email' => "alexandersilva@gmail.com",
            'password' => "Whisper5Harmony#Petal",
            'student_no' => '2111026',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Avery",
            'userlname' => "Morales",
            'usermiddle' => "Torres",
            'contact_no' => " 9178901236",
            'email' => "averytorres@gmail.com",
            'password' => "Lullaby3Eclipse^Stardust",
            'student_no' => '2111027',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Sebastian",
            'userlname' => "Rivera",
            'usermiddle' => "Gomez",
            'contact_no' => " 9189012347",
            'email' => "sebastiangomez@gmail.com",
            'password' => "MysticEnchant8Bloom",
            'student_no' => '2111028',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Aria",
            'userlname' => "Gonzales",
            'usermiddle' => "Perez",
            'contact_no' => " 9190123458",
            'email' => "ariaperez@gmail.com",
            'password' => "Evergreen1Ripple*Wander",
            'student_no' => '2111029',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Jackson",
            'userlname' => "Ortiz",
            'usermiddle' => "Navarro",
            'contact_no' => " 9101234569",
            'email' => "jacksonnavarro@gmail.com",
            'password' => "Enigma#Cascade6Sunrise",
            'student_no' => '21110030',
            'status' => 'Regular',
            'year_level_id' => 3,
            'sy' => 2
        ],
        [
            'userfname' => "Victoria",
            'userlname' => "Chavez",
            'usermiddle' => "Bautista",
            'contact_no' => " 9112345680",
            'email' => "victoriabautista@gmail.com",
            'password' => "Secret4Whisperer&Sunset",
            'student_no' => '2111031',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Oliver",
            'userlname' => "Lopez",
            'usermiddle' => "Alvarez",
            'contact_no' => " 9123456782",
            'email' => "oliveralvarez@gmail.com",
            'password' => "Blissful5Cascade*Haven",
            'student_no' => '2111032',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Ella",
            'userlname' => "Hernandez",
            'usermiddle' => "Ramos",
            'contact_no' => " 9134567893",
            'email' => "ellaramos@gmail.com",
            'password' => "WanderWhisper3Echo",
            'student_no' => '2111033',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Logan",
            'userlname' => "Mendoza",
            'usermiddle' => "Sanchez",
            'contact_no' => " 9145678904",
            'email' => "logansanchez@gmail.com",
            'password' => "Tranquility6Serenade#Dream",
            'student_no' => '2111034',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Madison",
            'userlname' => "Torres",
            'usermiddle' => "Aquino",
            'contact_no' => " 9156789015",
            'email' => "madisonaquino@gmail.com",
            'password' => "Adventure9Trail?Twilight",
            'student_no' => '2111035',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Daniel",
            'userlname' => "Castillo",
            'usermiddle' => "Navarro",
            'contact_no' => " 9167890126",
            'email' => "danielnavarro@gmail.com",
            'password' => "Wanderer3Whisper*Sunrise",
            'student_no' => '2111036',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Abigail",
            'userlname' => "Reyes",
            'usermiddle' => "Bautista",
            'contact_no' => " 9178901237",
            'email' => "abigailbautista@gmail.com",
            'password' => "Journey8Serenity#Harmony",
            'student_no' => '2111037',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Carter",
            'userlname' => "Diaz",
            'usermiddle' => "Silva",
            'contact_no' => " 9189012348",
            'email' => "cartersilva@gmail.com",
            'password' => "Serenade4Twilight",
            'student_no' => '2111038',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Samantha",
            'userlname' => "Gonzales",
            'usermiddle' => "Ortiz",
            'contact_no' => " 9190123459",
            'email' => "samanthaortiz@gmail.com",
            'password' => "Serenity7Whisper*Starlight",
            'student_no' => '2111039',
            'status' => 'Graduated',
            'year_level_id' => 4,
            'sy' => 1
        ],
        [
            'userfname' => "Nathaniel",
            'userlname' => "Ramirez",
            'usermiddle' => "Fernandez",
            'contact_no' => " 9101234570",
            'email' => "nathanielfernandez@gmail.com",
            'password' => "Adventure&Breeze2Tranquil",
            'student_no' => '2111040',
            'status' => 'Graduated',
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
                'cid' => 1,
                'sy' =>  $sys[$syindex]['sy'],
            ]);

            $curriculum_subjects = CurriculumSubjects::where('cid', 1)
                ->where('year_level_id', '<=',$this->students[$i]['year_level_id'])
                ->get();


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
