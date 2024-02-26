<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Implement ROLE BASED ACCESS
//TODO: Test API
//TODO: Register to api.php
//TODO: Add documentation
class ProgramsController extends Controller
{
    public function index(){
        return response()->json([
            ['status' => 'success'],
            Programs::all(),
        ], 200);
    }

    public function store(Request $request){

        $validate = Validator::make($request->all(),[
            'programcode'=> ['required', 'string'],
            'programdesc' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        return response()->json([
            ['status' => 'success'],
        Programs::create([
            'programcode' => $request->programcode,
            'programdesc' => $request->programdesc,
        ])
        ], 200);

    }

    public function show(Request $request, String $id){
        $program = Programs::find($id);

        if($program){
            return response()->json([
                ['status' => 'success'],
                $program
            ], 200);
        }
        return response()->json(['status' => 'not found'], 404);
    }

    public function update(Request $request, String $id){

        $validate = Validator::make($request->all(),[
            'programcode'=> ['required', 'string'],
            'programdesc' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $program = Programs::find($id);

        if($program){
            return response()->json([
                ['status' => 'success'],
            $program->update([
                'programcode' => $request->programcode,
                'programdesc' => $request->programdesc,
            ])
            ], 200);
        }
        return response()->json(['status' => 'not found'], 404);
    }

    public function delete(Request $request, String $id){
        $program = Programs::find($id);

        if($program){
            return response()->json([
                ['status' => 'success'],
            $program->delete()
            ], 200);
        }
        return response()->json(['status' => 'not found'], 404);
    }


}
