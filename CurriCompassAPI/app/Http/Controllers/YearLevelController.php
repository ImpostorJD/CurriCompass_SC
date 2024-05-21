<?php

namespace App\Http\Controllers;

use App\Models\YearLevel;
use Illuminate\Http\Request;

class YearLevelController extends Controller
{
    function __construct(){
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response()->json([
            ['status' => 'success'],
            YearLevel::all()
        ], 200);
    }

    public function show(string $id)
    {
        $year_level = YearLevel::where('year_level_id', $id)->first();

        if($year_level){
            return response()->json([
                ['status' => 'success'],
                $year_level
            ], 200);
        }

        return response()->json([
            ["status" => "not found"]
        ], 404);
    }
}
