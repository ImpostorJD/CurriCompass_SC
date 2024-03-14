<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
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
        $validate = Validator::make($request->all(), [
            'subjectname' => ['required', 'string'],
            'subjectcode' => ['required', 'string'],
            'subjectcredits' => ['required', 'int'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Subjects::where('subjectcode', '=', $request->subjectcode)->first();

        if($res != null){
            return response()->json([['status' => 'conflict'], "subject already existing"] ,409);
        }

        return response()->json([
            ['status' => 'resource created successfully'],
            Subjects::create([
                'subjectname' => $request['subjectname'],
                'subjectcode' => $request['subjectcode'],
                'subjectcredits' => $request['subjectcredits'],
            ])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Subjects::where('subjectid', '=', $id)->first();

        if($res != null) {
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
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Subjects::where('subjectid', '=', $id)->first();

        if($res != null) {
            if($res->subjectcode != $request->subjectcode && Subjects::where('subjectcode', $request->subjectcode)->first() != null) {
                return response()->json([['status' => 'conflict'], "Subject code is already in use."], 409);
            }

            return response()->json([
                ['status' => 'updated'],
                $res->update([
                    'subjectname' => $request['subjectname'],
                    'subjectcode' => $request['subjectcode'],
                    'subjectcredits' => $request['subjectcredits'],
                ])
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
