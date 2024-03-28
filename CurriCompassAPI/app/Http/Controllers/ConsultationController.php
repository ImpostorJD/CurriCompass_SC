<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationSubjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Add documentation
class ConsultationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Consultation::with('school_year')
                ->with(['curriculum' => function($query){
                    $query->with('program');
                    $query->orderBy('programid', 'DESC');
                }])
                ->with('semesters')
                ->where('srid', null)
                ->orderBy('year_level', 'ASC')
                ->orderBy('semid', 'DESC')
                ->get(),
        ], 200);
    }

    public function store(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'sy' => ['required', 'integer'],
            'cid' => ['required', 'integer'],
            'semid' => ['required', 'integer'],
            'srid' => ['nullable', 'integer'],
            'year_level' => ['required', 'string'],
            'section' => ['nullable', 'string'],
            'subjects' => ['required', 'array'],
            'subjects*.subjectid' => ['required', 'integer'],
            'subjects*.days' => ['required', 'string'],
            'subjects*.time' => ['required', 'string'],
        ]);

        if ($validators->fails()){
            return response()->json([
                ['status' => 'bad request'],
                $validators->errors()
            ], 400);
        }

        if(
            Consultation::where('sy', $request->sy)
                ->where('cid', $request->cid)
                ->where('srid', $request->srid)
                ->where('semid', $request->semid)
                ->where('year_level', $request->year_level)
                ->where('section', $request->section)
                ->first()
        ){
            return response()->json([
                ['status' => 'Conflict'],
                ['message' => 'consultation record with the combination already exists.']
            ], 409);
        }

        $consultation = Consultation::create([
            'sy' => $request->sy,
            'cid' => $request->cid,
            'semid' => $request->semid,
            'year_level' => $request->year_level,
            'srid' => $request->srid
        ]);

        foreach($request->subjects as $subject){
            ConsultationSubjects::create([
                'coid' => $consultation->coid,
                'subjectid' => $subject['subjectid'],
                'days' => $subject['days'],
                'time' => $subject['time'],
            ]);
        }

        return response()->json([
            ['status' => 'success'],
            $consultation
        ], 200);
    }

    public function show(string $id)
    {
        $res = Consultation::where('coid', $id)
            ->with(['curriculum' => function($query){
                $query->with('program');
            }])
            ->with('semesters')
            ->with('student_record')
            ->with(['consultation_subjects' => function($query){
                $query->with('subjects');
            }])->first();

        if($res){
            return response()->json([
                ['status' => 'success'],
                $res,
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ["message" => 'resource not found!'],
        ], 404);
    }

    public function update(Request $request, string $id)
    {
        $validators = Validator::make($request->all(), [
            'sy' => ['required', 'integer'],
            'cid' => ['required', 'integer'],
            'semid' => ['required', 'integer'],
            'srid' => ['nullable', 'integer'],
            'year_level' => ['required', 'string'],
            'section' => ['nullable', 'string'],
            'subjects' => ['required', 'array'],
            'subjects*.subjectid' => ['required', 'integer'],
            'subjects*.days' => ['required', 'string'],
            'subjects*.time' => ['required', 'string'],
        ]);

        if ($validators->fails()){
            return response()->json([
                ['status' => 'bad request'],
                $validators->errors()
            ], 400);
        }
        $res = Consultation::where('coid', $id)
            ->with('curriculum')
            ->with('semesters')
            ->with('student_record')
            ->with(['consultation_subjects' => function($query){
                $query->with('subjects');
            }])->first();

        if($res){
            $recordToCompare = Consultation::where('sy', $request->sy)
                ->where('cid', $request->cid)
                ->where('srid', $request->srid)
                ->where('semid', $request->semid)
                ->where('section', $request->section)
                ->where('year_level', $request->year_level)
                ->first();

            if ($recordToCompare &&
                (
                    $res->cid != $request->cid &&
                    $res->srid != $request->srid &&
                    $res->section != $request->section &&
                    $res->semid != $request->semid &&
                    $res->year_level != $request->year_level
                )
            ){
                return response()->json([
                    ['status' => 'Conflict'],
                    ['message' => 'consultation record with the combination already exists.']
                ], 409);
            }

            $res->update([
                'sy' => $request->sy,
                'cid' => $request->cid,
                'semid' => $request->semid,
                'section' => $request->section,
                'year_level' => $request->year_level,
                'srid' => $request->srid
            ]);

            $res->consultation_subjects()->delete();

            foreach($request->subjects as $subject){
                ConsultationSubjects::create([
                    'coid' => $res->coid,
                    'subjectid' => $subject['subjectid'],
                    'days' => $subject['days'],
                    'time' => $subject['time'],
                ]);
            }

        }

        return response()->json([
            ['status' => 'not found'],
            ["message" => 'resource not found!'],
        ], 404);
    }


    public function destroy(string $id)
    {
        $res = Consultation::where('coid', $id)
            ->with('curriculum')
            ->with('semesters')
            ->with('student_record')
            ->with(['consultation_subjects' => function($query){
                $query->with('subjects');
            }])->first();

        if($res){

            $res->consultation_subjects()->delete();

            $res->enlistment()->delete();

            return response()->json([
                ['status' => 'success'],
                $res->delete(),
            ], 200);

        }
        return response()->json([
            ['status' => 'not found'],
            ["message" => 'resource not found!'],
        ], 404);

    }
}
