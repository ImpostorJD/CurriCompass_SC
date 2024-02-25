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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            Role::all()
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
