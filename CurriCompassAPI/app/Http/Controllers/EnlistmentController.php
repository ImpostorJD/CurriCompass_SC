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
        $this->middleware('auth:api')->except('store');
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
        $validators = Validator::make($request->all(), [
            'student_no' => ['required', 'string'],
        ]);

        if ($validators->fails()){
            return response()->json([
                ['status' => 'bad request'],
                $validators->errors()
            ], 400);
        }

        $student = StudentRecord::where('student_no', $request->student_no)
            ->first();

        if($student){
            $latestYear = SchoolYear::orderBy('sy', 'DESC')->first();

            if ($student->status == "Regular"){
                //Get latest consultation
                $consultationLatest = Consultation::where('sy', $latestYear->sy)
                    ->where('cid', $student->cid)
                    ->where('year_level', $student->year_level)
                    ->orderBy('semid', 'DESC')
                    ->first();

                $consultationsBlocks = Consultation::where('sy', $latestYear->sy)
                    ->where('cid', $student->cid)
                    ->where('year_level', $student->year_level)
                    ->where('semid', $consultationLatest)
                    ->orderBy('semid', 'DESC')
                    ->get();

                //filters students volume exceeding from 45 to the next block
                if(sizeof($consultationsBlocks) > 1){
                    foreach($consultationsBlocks as $block){
                        $enlistment = Enlistment::where('coid', $block->coid)
                            ->with('student_record')
                            ->get();
                        if(sizeof($enlistment->student_record) < 45){
                            $consultationLatest = $block;
                            break;
                        }
                    }
                }

                //validate if user already have enlistment
                $enlistment = Enlistment::where('coid', $consultationLatest->coid)
                    ->where('srid', $student->srid)->first();

                if($enlistment == null){
                    return response()->json([
                        ['status' => 'success'],
                        Enlistment::create([
                            'srid' => $student->srid,
                            'coid' => $consultationLatest->coid,
                        ]),
                    ], 200);
                }
            }

        }

    }

    public function getRegularEnlistment(Request $request, String $id){
            $student_record = StudentRecord::where('student_no', $id)
                ->with(['enlistment'=> function($query){
                    $query->with(['consultation' => function($query){
                        $query->with(['consultation_subjects' => function($query){
                            $query->with('subjects');
                        }]);
                        $query->with('semesters');
                        $query->with('school_year');
                    }]);
                }])
                ->with(['curriculum' => function($query){
                    $query->with('program');
                }])
                ->first();

            $latestYear = SchoolYear::orderBy('sy', 'DESC')->first();

            $hasLatestEnlistment = false;
            if(
                Enlistment::where('srid', $student_record->srid)
                    ->with(['consultation'=> function($query) use ($latestYear){
                        $query->where('sy', $latestYear->sy);
                    }])->first()
            ){
                $hasLatestEnlistment = true;
            }

            return response()->json([
                ['status' => 'success'],
                $student_record,
                ['hasLatest' => $hasLatestEnlistment]
            ], 200);
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
