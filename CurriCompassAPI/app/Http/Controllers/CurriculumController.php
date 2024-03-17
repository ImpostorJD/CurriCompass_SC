<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Implement ROLE BASED ACCESS
//TODO: Add documentation
class CurriculumController extends Controller
{
    public function index(){

        return response()->json([
            ['status' => "success"],
            Curriculum::with('program')->get()
        ]);
    }

    public function show(Request $request, String $id){
        $curriculum = Curriculum::where('cid', $id)
            ->with(['curriculum_subjects'=> function($query) {
                $query->with('subjects');
                $query->with('semesters');
            }])
            ->first();

        if ($curriculum != null) {
           return response()->json([
                ['status' => 'success'],
                $curriculum
           ], 200);
        }

        return response()->json(['status' => 'not found'], 404);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'programid' => ['required', 'integer'],
            'specialization' => ['nullable', 'string'],
            'curriculum_subjects' => ['required', 'array'],
            'curriculum_subjects*.semid' => ['required', 'integer'],
            'curriculum_subjects*.subjectid' => ['required', 'integer'],
            'curriculum_subjects*.year_level' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $existing = Curriculum::where('programid', $request->programid)
            ->where('specialization', $request->specialization)->first();
        if($existing != null){
            return response()->json([['status' => 'conflict'], "Combination already exists."], 409);
        }

        $curriculum = Curriculum::create([
            'programid' => $request->programid,
            'specialization' => $request->specialization,
        ]);

        foreach($request->curriculum_subjects as $subject) {
            $curriculum->curriculum_subjects()->insert([
                'cid' => $curriculum->cid,
                'subjectid' => $subject['subjectid'],
                'semid' => $subject['semid'],
                'year_level'=>$subject['year_level'],
            ]);
        }

        return response()->json([
            ['status' => 'success'],
            $curriculum
        ], 200);
    }

    public function destroy(Request $request, String $id){
        $curriculum = Curriculum::where('cid', $id)->first();

        if ($curriculum != null){
            $curriculum->delete();
            return response()->json(['status' => 'successfully deleted'], 200);
        }
        return response()->json(['status' => 'not found'], 404);
    }

    public function update(Request $request, String $id)
    {
        $validate = Validator::make($request->all(), [
            'programid' => ['required', 'integer'],
            'specialization' => ['nullable', 'string'],
            'curriculum_subjects' => ['required', 'array'],
            'curriculum_subjects*.semid' => ['required', 'integer'],
            'curriculum_subjects*.subjectid' => ['required', 'integer'],
            'curriculum_subjects*.year_level' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }
        $existing = Curriculum::where('programid', $request->programid)
            ->where('specialization', $request->specialization)->first();
        $curriculum = Curriculum::find($id);

        if($existing != null && ($request->programid != $existing->programid && $request->specialization != $existing->specialization)){
            return response()->json([['status' => 'conflict'], "Combination already exists."], 409);
        }

        $curriculum->update([
            'programid' => $request->programid,
            'specialization' => $request->specialization,

        ]);

        $curriculum->curriculum_subjects()->delete();

        foreach($request->curriculum_subjects as $subject) {
            $curriculum->curriculum_subjects()->insert([
                'cid' => $curriculum->cid,
                'subjectid' => $subject['subjectid'],
                'semid' => $subject['semid'],
                'year_level'=>$subject['year_level'],
            ]);
        }

        return response()->json([
            ['status' => 'success'],
            $curriculum
        ], 200);
    }
}
