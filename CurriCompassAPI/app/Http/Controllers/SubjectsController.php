<?php

namespace App\Http\Controllers;

use App\Models\CourseAvailability;
use App\Models\Pre_Requisites;
use App\Models\Pre_Requisites_Subjects;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Add Documentation
class SubjectsController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Subjects::all()
            ], 200);
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'subjectname' => ['required', 'string'],
            'subjectcode' => ['required', 'string'],
            'subjectcredits' => ['required', 'int'],
            'subjectunitlab' => ['required', 'integer'],
            'subjectunitlec' => ['required', 'integer'],
            'subjecthourslec' => ['required', 'decimal:0'],
            'subjecthourslab' => ['required', 'decimal:0'],
            'semavailability' => ['required', 'integer'],
            'year_level' => ['nullable', 'string'],
            'subjects' => ['nullable', 'array'],
            'subjects*.subjectid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Subjects::where('subjectcode', $request->subjectcode)->first();
        if($res != null){
            return response()->json([['status' => 'conflict'], "subject already existing"] ,409);
        }

        $subject = Subjects::create([
            'subjectname' => $request['subjectname'],
            'subjectcode' => $request['subjectcode'],
            'subjectcredits' => $request['subjectcredits'],
            'subjectunitlab' => $request['subjectunitlab'],
            'subjectunitlec' => $request['subjectunitlec'],
            'subjecthourslec' => $request['subjecthourslec'],
            'subjecthourslab' => $request['subjecthourslab'],
        ]);

        $pre_requisite = Pre_Requisites::create([
            'subjectid' => $subject->subjectid,
            'year_level' => $request->year_level,
           // 'completion' => $request->completion,
        ]);

        CourseAvailability::create([
            'semid' => $request['semavailability'],
            'subjectid' => $subject->subjectid,
        ]);

        if(sizeof($request->subjects) > 0) {
            foreach($request->subjects as $subject){
                Pre_Requisites_Subjects::create([
                    "prid" => $pre_requisite->prid,
                    "subjectid" => $subject['subjectid'],
                ]);
            }
        }

        return response()->json([
            ['status' => 'resource created successfully'],
            $subject,
        ], 201);
    }

    public function show(string $id)
    {
        $res = Subjects::with(['pre_requisites' => function($query){
            $query->with('pre_requisites_subjects');
        }])->with('course_availability')
            ->where('subjectid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'success'],
                $res], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ], 404);

    }

    public function update(Request $request, string $id)
    {

        $validate = Validator::make($request->all(), [
            'subjectname' => ['required', 'string'],
            'subjectcode' => ['required', 'string'],
            'subjectcredits' => ['required', 'int'],
            'subjectunitlab' => ['required', 'integer'],
            'subjectunitlec' => ['required', 'integer'],
            'subjecthourslec' => ['required', 'decimal:0'],
            'subjecthourslab' => ['required', 'decimal:0'],
            'semavailability' => ['required', 'integer'],
            'year_level' => ['nullable', 'string'],
            'subjects' => ['nullable', 'array'],
            'subjects*.subjectid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Subjects::where('subjectid', '=', $id)->with('course_availability')->first();

        if($res != null) {
            if ($res->subjectcode != $request->subjectcode) return response()->json([
                ['status' => 'conflict'],
                ["message" => "Subject code is already in use."]
            ], 409);
            $res->update([
                'subjectname' => $request['subjectname'],
                'subjectcode' => $request['subjectcode'],
                'subjectcredits' => $request['subjectcredits'],
                'subjectunitlab' => $request['subjectunitlab'],
                'subjectunitlec' => $request['subjectunitlec'],
                'subjecthourslec' => $request['subjecthourslec'],
                'subjecthourslab' => $request['subjecthourslab'],
            ]);

            $pre_requisite = Pre_Requisites::where('subjectid', $id)->first();

            $res->course_availability()->update([
                'semid' => $request['semavailability'],
            ]);

            $pre_requisite->update([
                'year_level' => $request->year_level,
                //'completion' => $request->completion,
            ]);

            Pre_Requisites_Subjects::where('prid', $pre_requisite->prid)->delete();

            if(sizeof($request->subjects) > 0) {
                foreach($request->subjects as $subject){
                    Pre_Requisites_Subjects::create([
                        "prid" => $pre_requisite->prid,
                        "subjectid" => $subject['subjectid'],
                    ]);
                }
            }

            return response()->json([
                ['status' => 'updated'],
                $res
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }


    public function destroy(string $id)
    {
        $res = Subjects::where('subjectid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'updated'],
                $res->delete(),
            ], 200);
        }
        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }

    public function course_availability(Request $request){
        return response()->json([
            ['status' => 'success'],
            CourseAvailability::with('subjects')
                ->orderBy('semid', 'ASC')
                ->get(),
            ], 200);
    }

    public function course_availability_update(Request $request, String $id){
        $validate = Validator::make($request->all(), [
            'semavailability' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = CourseAvailability::where('subjectid', $id)->first()->delete();

        if($res != null) {
            return response()->json([
                ['status' => 'success'],
                CourseAvailability::create([
                    'semid' => $request['semavailability'],
                    'subjectid' => $id,
                ])
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }
}
