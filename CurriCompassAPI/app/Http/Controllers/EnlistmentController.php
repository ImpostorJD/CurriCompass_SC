<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Enlistment;
use App\Models\SchoolYear;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//TODO: Add documentation
class EnlistmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response()->json([
            ['status' => 'not found'],
            Enlistment::all(),
        ], 200);
    }

    public function store(Request $request)
    {
        //TODO: Implement algorithmic enlistment
    }

    public function show(Request $request, String $id)
    {
        $res = Enlistment::where('coid', $id)->first();

        if($res){
            return response()->json([
                ['status' => 'success'],
                $res
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ['message' => 'resource not found']
        ], 404);
    }

    public function update(Request $request, String $id)
    {
        //TODO: update the enlistment individually
    }

    public function destroy(Request $request, String $id)
    {
        //TODO: delete the enlistment individually
    }
}
