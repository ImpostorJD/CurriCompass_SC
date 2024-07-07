<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Add documentation
class ProgramsController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }

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

        if(Programs::where('programcode',$request->programcode)->first() != null){
            return response()->json([['status' => 'conflict'], "program code already in use!"], 409);
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

        $program = Programs::where('programid', $id)->first();
        $existing = Programs::where('programcode', $request->programcode)->first();

        if($program) {
            if($existing != null && $program->programid != $existing->existing->programid && $program->programcode == $existing->programcode) {
                return response()->json([['status' => 'conflict'], "Program code is already in use."], 409);
            }

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

    public function destroy(Request $request, String $id){
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
