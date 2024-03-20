<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

//TODO: Add Documentation
//TODO: Add Role-based access
class RoleController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Role::where('rolename', '!=', 'Admin')->get()
            ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'rolename' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }
        return response()->json([
            ['status' => 'resource created successfully'],
            Role::create([
                'rolename' => $request['rolename'],
            ])
        ], 201);
    }

    public function show(String $id)
    {
        $res = Role::where('roleid', '=', $id)->first();

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
            'rolename' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $res = Role::where('roleid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'updated'],
                $res->update([
                    'rolename' => $request->rolename,
                ])
            ], 200);
        }
        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }

    public function destroy(string $id)
    {
        $res = Role::where('roleid', '=', $id)->first();

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
