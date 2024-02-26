<?php

namespace App\Http\Controllers;

use App\Models\Semesters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Test API
//TODO: Register to api.php
//TODO: Implement ROLE BASED ACCESS
//TODO: Add documentation
class SemesterController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Semesters::all()
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'semdesc' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }
        return response()->json([
            ['status' => 'resource created successfully'],
            Semesters::create([
                'semdesc' => $request['semdesc'],
            ])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, String $id)
    {
        $res = Semesters::where('semid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'success'],
                $res
            ], 200);
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
            'semdesc' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Semesters::where('semid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'updated'],
                $res->update([
                    'semdesc' => $request->semdesc,
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
        $res = Semesters::where('semid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'success'],
                $res->delete()
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }
}
