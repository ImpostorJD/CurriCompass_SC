<?php

namespace App\Http\Controllers;

use App\Models\Semesters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Implement ROLE BASED ACCESS
//TODO: Add documentation
class SemesterController extends Controller
{

    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Semesters::all()
            ], 200);
    }

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
