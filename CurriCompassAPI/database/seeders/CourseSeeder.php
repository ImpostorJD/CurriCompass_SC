<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects') ->insert([

           [
                //1
                "subjectname" => "Introduction to Computing",
                "subjectcode" => "COMP 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],
           [
                //2
                "subjectname" => "Computer Programming 1",
                "subjectcode" => "COMP 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],

            [
                //3
                "subjectname" => "Mathematics in the Modern World",
                "subjectcode" => "GEd 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //4
                "subjectname" => "Science Technology and Society",
                "subjectcode" => "GEd 7",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //5
                "subjectname" => "Discrete Structure",
                "subjectcode" => "ITP 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],
            [
                //6
                "subjectname" => "Social Arts",
                "subjectcode" => "SOAr1",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //7
                "subjectname" => "Computer Programming 2",
                "subjectcode" => "COMP 3",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: subjectid:2
            ],

            [
                //8
                "subjectname" => "Data Management and Computing",
                "subjectcode" => "GEd 10",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],

            [
                //9
                "subjectname" => "Database Design and Computing",
                "subjectcode" => "COMP 14",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],

            [
                //10
                "subjectname" => "Readings in the Philippine History",
                "subjectcode" => "GEd 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //11
                "subjectname" => "The Contemporary World",
                "subjectcode" => "GEd 3",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //12
                "subjectname" => "Social Arts 2",
                "subjectcode" => "SOAr2",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: subjectid: 6
            ],

            [
                //13
                "subjectname" => "Fitness and Wellness",
                "subjectcode" => "PE 1",
                'subjectcredits' => 2,
                'subjectunitlab' => 2,
                'subjectunitlec' => 0,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //14
                "subjectname" => "Integrative Programming and Technologies",
                "subjectcode" => "ICT 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 2,
                'subjectunitlec' => 1,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: subjectid: 7
            ],

            [
                //15
                "subjectname" => "Networking 1",
                "subjectcode" => "NET 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 2,
                'subjectunitlec' => 1,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: subjectid: 1
            ],

            [
                //16
                "subjectname" => "Understanding the Self",
                "subjectcode" => "GEd 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //17
                "subjectname" => "Purposive Communication",
                "subjectcode" => "GEd 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //18
                "subjectname" => "Ethics",
                "subjectcode" => "GEd 8",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //19
                "subjectname" => "Logic Design and Basic Electronics",
                "subjectcode" => "ICT 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 3,
                'subjectunitlec' => 0,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //20
                "subjectname" => "Dance and Related Movement",
                "subjectcode" => "PE 2",
                'subjectcredits' => 2,
                'subjectunitlab' => 2,
                'subjectunitlec' => 0,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 0,
                //pre-req: subjectid: 13
            ],

            [
                //21
                "subjectname" => "Data Structures and Algorithm",
                "subjectcode" => "COMP 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 2
            ],

            [
                //22
                "subjectname" => "Object Oriented Programming",
                "subjectcode" => "ITP 3",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 2, tsaka ICT 5... (excluded kasi di ko makita)
            ],

            [
                //23
                "subjectname" => "Operating System",
                "subjectcode" => "ITP 13",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 15
            ],

            [
                //24
                "subjectname" => "Recent Trends, Social and Professional Issues",
                "subjectcode" => "ITP 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //25
                "subjectname" => "Art Appreciation",
                "subjectcode" => "GEd 6",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //26
                "subjectname" => "Individual and Dual Sports",
                "subjectcode" => "PE 3",
                'subjectcredits' => 2,
                'subjectunitlab' => 2,
                'subjectunitlec' => 0,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 0,
                //pre-req: 20
            ],

            [
                //27
                "subjectname" => "NSTP 1 - Civic Welfare Training Service 1/NSTP1 - Reserve Officer Training Course 1",
                "subjectcode" => "CWTS 1/ROTC 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //28
                "subjectname" => "Cloud Computing",
                "subjectcode" => "ITP 14",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 19
            ],

            [
                //29
                "subjectname" => "Internet of Things",
                "subjectcode" => "ICT 7",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 19
            ],

            [
                //30
                "subjectname" => "Networking 2",
                "subjectcode" => "NET 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 15
            ],

            [
                //31
                "subjectname" => "Information Management",
                "subjectcode" => "COMP 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: ICT 5... di ko makita
            ],

            [
                //32
                "subjectname" => "Quantitative Methods",
                "subjectcode" => "ITP 9",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 5
            ],

            [
                //33
                "subjectname" => "Team Sports",
                "subjectcode" => "PE 4",
                'subjectcredits' => 2,
                'subjectunitlab' => 2,
                'subjectunitlec' => 0,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 0,
                //pre-req: 26
            ],

            [
                //34
                "subjectname" => "Developing .NET Solution",
                "subjectcode" => "WAM 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 22
            ],

            [
                //35
                "subjectname" => "NSTP 2 - Civic Welfare Training Service 2/NTSP 2 - Reserve Officer Training Course 2",
                "subjectcode" => "CWTS 2/ROTC 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 27
            ],

            [
                //36
                "subjectname" => "Graphics and Visual Computing",
                "subjectcode" => "ITP 8",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],

            [
                //37
                "subjectname" => "Application Development and Emerging Technology",
                "subjectcode" => "COMP 6",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 14
            ],

            [
                //38
                "subjectname" => "Drawing and Color Management using Wacom Tablet and Photoshop",
                "subjectcode" => "AGD 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],

            [
                //39
                "subjectname" => "Theory of Automata",
                "subjectcode" => "ITP 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 5
            ],

            [
                //40
                "subjectname" => "Effective Writing",
                "subjectcode" => "EL 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //41
                "subjectname" => "Human Computer Interaction",
                "subjectcode" => "ICT 9",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //42
                "subjectname" => "Differential and Integral Calculus",
                "subjectcode" => "CALC 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //43
                "subjectname" => "Elective 1 (Open Source Technology)",
                "subjectcode" => "ITE 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 7
            ],

            [
                //44
                "subjectname" => "Information Assurance and Security",
                "subjectcode" => "ITP 10",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 15, 31
            ],

            [
                //45
                "subjectname" => "Software Engineering",
                "subjectcode" => "ICT 8",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 22
            ],

            [
                //46
                "subjectname" => "Digital Video and Audio Design",
                "subjectcode" => "AGD 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 38
            ],

            [
                //47
                "subjectname" => "Methods of Research",
                "subjectcode" => "RES 0",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 3rd year
            ],

            [
                //48
                "subjectname" => "Elective 4 (Intelligent System)",
                "subjectcode" => "ITE 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //49
                "subjectname" => "Elective 3 (Data Analytics)",
                "subjectcode" => "ITE 6",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: ICT 5... di ko makita
            ],

            [
                //50
                "subjectname" => "Graphic Design and Multimedia Studio 1",
                "subjectcode" => "AGD 3",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 46
            ],

            [
                //51
                "subjectname" => "Game Programming 1",
                "subjectcode" => "AGD 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 46
            ],

            [
                //52
                "subjectname" => "Programming Languages",
                "subjectcode" => "ITP 11",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 21
            ],

            [
                //53
                "subjectname" => "Technopreneurship",
                "subjectcode" => "COMP 13",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 37
            ],

            [
                //54
                "subjectname" => "Computing Thesis 1",
                "subjectcode" => "THE 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 47
            ],

            [
                //55
                "subjectname" => "Graphic Design and Multimedia Studio 2",
                "subjectcode" => "AGD 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 51
            ],

            [
                //56
                "subjectname" => "Rizal",
                "subjectcode" => "GEd 9",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //57
                "subjectname" => "Customer Service Relation",
                "subjectcode" => "CSR 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //58
                "subjectname" => "People and the Environment",
                "subjectcode" => "ENV 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //59
                "subjectname" => "Foreign Language",
                "subjectcode" => "FL 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //60
                "subjectname" => "Practicum",
                "subjectcode" => "ICT 18",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 4th Year
            ],

            [
                //61
                "subjectname" => "Web and Mobile Application Development for Android Studio 1",
                "subjectcode" => "WAM 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 7
            ],

            [
                //62
                "subjectname" => "Platform Technologies",
                "subjectcode" => "ICT 12",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 7
            ],

            [
                //63
                "subjectname" => "Web and Mobile Application Development for iOS",
                "subjectcode" => "WAM 3",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 34, 61
            ],

            [
                //64
                "subjectname" => "Web and Mobile Application Development for Android Studio 2",
                "subjectcode" => "WAM 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 61
            ],

            [
                //65
                "subjectname" => "Capstone Project 1",
                "subjectcode" => "CAP 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 47
            ],

            [
                //66
                "subjectname" => "System Integration, Architecture and Maintenance",
                "subjectcode" => "ICT 10",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: none
            ],

            [
                //67
                "subjectname" => "Capstone Project 2",
                "subjectcode" => "CAP 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 65
            ],

            [
                //68
                "subjectname" => "Fundamentals of Accounting with SAP",
                "subjectcode" => "ACC 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //69
                "subjectname" => "Software Quality Assurance",
                "subjectcode" => "ICT 16",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 22
            ],

            [
                //70
                "subjectname" => "Advanced Web and Mobile Application Development",
                "subjectcode" => "WAM 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 63, 64
            ],

            [
                //71
                "subjectname" => "Business Process Outsourcing 1",
                "subjectcode" => "BPO 1",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],

            [
                //72
                "subjectname" => "Business Communication",
                "subjectcode" => "BPO 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 17
            ],

            [
                //73
                "subjectname" => "Business Process Outsourcing 2",
                "subjectcode" => "BPO 3",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 71
            ],

            [
                //74
                "subjectname" => "Service Culture",
                "subjectcode" => "BPO 4",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 71
            ],

            [
                //75
                "subjectname" => "Principles of Systems Thinking",
                "subjectcode" => "BPO 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 73, 74
            ],
            [
                //76
                "subjectname" => "Algorithm and Complexity",
                "subjectcode" => "ITP 6",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 21
            ],

            [
                //77
                "subjectname" => "Advance Speech and Oral Communication",
                "subjectcode" => "ENG 22",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 17
            ],

            [
                //78
                "subjectname" => "Game Programming 2",
                "subjectcode" => "AGD 6",
                'subjectcredits' => 3,
                'subjectunitlab' => 1,
                'subjectunitlec' => 2,
                'subjecthourslec' => 2.67,
                'subjecthourslab' => 4,
                //pre-req: 51
            ],

            [
                //79
                "subjectname" => "Computing Thesis 2",
                "subjectcode" => "THE 2",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: 54
            ],

            [
                //80
                "subjectname" => "Elective 5 (Parallel and Distributed Computing)",
                "subjectcode" => "ITE 5",
                'subjectcredits' => 3,
                'subjectunitlab' => 0,
                'subjectunitlec' => 3,
                'subjecthourslec' => 4,
                'subjecthourslab' => 0,
                //pre-req: none
            ],
        ]);

        DB::table('pre__requisites')->insert([
            [
                'subjectid' => 1,
                'year_level_id' => null,
                //'completion' => null
            ],

            [
                'subjectid' => 2,
                'year_level_id' => null,
                //'completion' => null
            ],

            [
                'subjectid' => 3,
                'year_level_id' => null,
                //'completion' => null
            ],

            [
                'subjectid' => 4,
                'year_level_id' => null,
                //'completion' => null
            ],

            [
                'subjectid' => 5,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 6,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 7,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 8,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 9,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 10,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 11,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 12,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 13,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 14,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 15,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 16,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 17,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 18,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 19,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 20,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 21,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 22,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 23,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 24,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 25,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 26,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 27,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 28,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 29,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 30,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 31,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 32,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 33,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 34,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 35,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 36,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 37,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 38,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 39,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 40,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 41,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 42,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 43,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 44,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 45,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 46,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 47,
                'year_level_id' => 3,
                //'completion' => null
            ],
            [
                'subjectid' => 48,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 49,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 50,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 51,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 52,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 53,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 54,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 55,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 56,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 57,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 58,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 59,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 60,
                'year_level_id' => 4,
                //'completion' => null
            ],
            [
                'subjectid' => 61,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 62,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 63,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 64,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 65,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 66,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 67,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 68,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 69,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 70,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 71,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 72,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 73,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 74,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 75,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 76,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 77,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 78,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 79,
                'year_level_id' => null,
                //'completion' => null
            ],
            [
                'subjectid' => 80,
                'year_level_id' => null,
                //'completion' => null
            ],
        ]);

        DB::table('pre__requisites__subjects')->insert([
            [
                'prid' => 7,
                'subjectid' => 2,
            ],
            [
                'prid' => 12,
                'subjectid' => 6,
            ],
            [
                'prid' => 14,
                'subjectid' => 7,
            ],
            [
                'prid' => 15,
                'subjectid' => 1,
            ],
            [
                'prid' => 20,
                'subjectid' => 13,
            ],
            [
                'prid' => 21,
                'subjectid' => 2,
            ],
            [
                'prid' => 22,
                'subjectid' => 2,
            ],
            [
                'prid' => 22,
                'subjectid' => 9,
            ],
            [
                'prid' => 23,
                'subjectid' => 15,
            ],
            [
                'prid' => 26,
                'subjectid' => 20,
            ],
            [
                'prid' => 28,
                'subjectid' => 19,
            ],
            [
                'prid' => 29,
                'subjectid' => 19,
            ],
            [
                'prid' => 30,
                'subjectid' => 15,
            ],
            [
                'prid' => 31,
                'subjectid' => 9,
            ],
            [
                'prid' => 32,
                'subjectid' => 5,
            ],
            [
                'prid' => 33,
                'subjectid' => 26,
            ],
            [
                'prid' => 34,
                'subjectid' => 22,
            ],
            [
                'prid' => 35,
                'subjectid' => 27,
            ],
            [
                'prid' => 37,
                'subjectid' => 14,
            ],
            [
                'prid' => 39,
                'subjectid' => 5,
            ],
            [
                'prid' => 43,
                'subjectid' => 7,
            ],
            [
                'prid' => 44,
                'subjectid' => 15,
            ],
            [
                'prid' => 44,
                'subjectid' => 31,
            ],
            [
                'prid' => 45,
                'subjectid' => 22,
            ],
            [
                'prid' => 46,
                'subjectid' => 38,
            ],
            [
                'prid' => 49,
                'subjectid' => 9,
            ],
            [
                'prid' => 50,
                'subjectid' => 46,
            ],
            [
                'prid' => 51,
                'subjectid' => 46,
            ],
            [
                'prid' => 52,
                'subjectid' => 21,
            ],
            [
                'prid' => 53,
                'subjectid' => 37,
            ],
            [
                'prid' => 54,
                'subjectid' => 47,
            ],
            [
                'prid' => 55,
                'subjectid' => 51,
            ],
            [
                'prid' => 61,
                'subjectid' => 7,
            ],
            [
                'prid' => 62,
                'subjectid' => 7,
            ],
            [
                'prid' => 63,
                'subjectid' => 34,
            ],
            [
                'prid' => 63,
                'subjectid' => 61,
            ],
            [
                'prid' => 64,
                'subjectid' => 61,
            ],
            [
                'prid' => 65,
                'subjectid' => 47,
            ],
            [
                'prid' => 67,
                'subjectid' => 65,
            ],
            [
                'prid' => 69,
                'subjectid' => 22,
            ],
            [
                'prid' => 70,
                'subjectid' => 63,
            ],
            [
                'prid' => 70,
                'subjectid' => 64,
            ],
            [
                'prid' => 72,
                'subjectid' => 17,
            ],
            [
                'prid' => 73,
                'subjectid' => 71,
            ],
            [
                'prid' => 74,
                'subjectid' => 71,
            ],
            [
                'prid' => 75,
                'subjectid' => 73,
            ],
            [
                'prid' => 75,
                'subjectid' => 74,
            ],
            [
                'prid' => 76,
                'subjectid' => 21,
            ],
            [
                'prid' => 77,
                'subjectid' => 17,
            ],
            [
                'prid' => 78,
                'subjectid' => 51,
            ],
            [
                'prid' => 79,
                'subjectid' => 54,
            ],
        ]);

    }
}
