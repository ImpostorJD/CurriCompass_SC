<?php

namespace App\Http\Controllers;

use App\Models\Pre_Requisites;
use App\Models\Pre_Requisites_Subjects;
use App\Models\Subjects;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Add Documentation
//TODO: Add Role-based access
class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Subjects::all()
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd($request->subjects);
        $validate = Validator::make($request->all(), [
            'subjectname' => ['required', 'string'],
            'subjectcode' => ['required', 'string'],
            'subjectcredits' => ['required', 'int'],
            'subjecttype' => ['required', 'string'],
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
            'subjecttype' => $request['subjecttype'],
        ]);

        $pre_requisite = Pre_Requisites::create([
            'subjectid' => $subject->subjectid,
            'year_level' => $request->year_level,
            'completion' => $request->completion,
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Subjects::with('pre_requisites')
            ->where('subjectid', '=', $id)->first();

        if($res != null) {
            $pre_requisites_subjects = Pre_Requisites_Subjects::where('prid', $res->pre_requisites->prid)->get();
            $res['pre_requisites_subjects'] = $pre_requisites_subjects;
            return response()->json([
                ['status' => 'success'],
                $res], 200);
        }
        return response()->json([
            ['status' => 'not found'],
            ], 404);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validate = Validator::make($request->all(), [
            'subjectname' => ['required', 'string'],
            'subjectcode' => ['required', 'string'],
            'subjectcredits' => ['required', 'int'],
            'subjecttype' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Subjects::where('subjectid', '=', $id)->first();

        if($res != null && $res->subjectcode != $request->subjectcode) {
            return response()->json([['status' => 'conflict'], "Subject code is already in use."], 409);
            $res->update([
                'subjectname' => $request['subjectname'],
                'subjectcode' => $request['subjectcode'],
                'subjectcredits' => $request['subjectcredits'],
                'subjecttype' => $request['subjecttype'],
            ]);

            $pre_requisite = Pre_Requisites::where('subjectid', $id)->first();
            $pre_requisite->update([
                'year_level' => $request->year_level,
                'completion' => $request->completion,
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

    /**
     * Remove the specified resource from storage.
     */
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
}
