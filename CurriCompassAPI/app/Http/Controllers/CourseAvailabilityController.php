<?php

namespace App\Http\Controllers;

use App\Models\CourseAvailability;
use App\Models\EnlistmentSubjects;
use App\Models\SemSy;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseAvailabilityController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $currentsemsy = SemSy::orderBy('semsyid', 'desc')->first();
        if(!$currentsemsy){
            return response()->json([
             "no semester school year yet"
            ], 200);
        }
        return response()->json([
            ['status' => 'success'],
            CourseAvailability::whereHas('semester_sy', function($query) use($currentsemsy){
                $query->where('semsyid', $currentsemsy->semsyid);
            })
            ->with(['semester_sy'=> function($query){
                $query->with('school_year');
                $query->with('semester');
            }])->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'coursecode' => ['required', 'string'],
            'semsyid' => ['required', 'integer'],
            'time' => ['required', 'string'],
            'section' => ['required', 'string'],
            'section_limit' => ['required', 'integer'],
            'days' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $current_record = CourseAvailability::where('coursecode', $request['coursecode'])
            ->where('semsyid', $request['semsyid'])
            ->where('time', $request['time'])
            ->where('section', $request['section'])
            ->where('days', $request['days'])
            ->first();

        if($current_record){
            return response()->json([['status' => 'duplicate'], $validate->errors()] ,409);
        }

        return response()->json([
            ['status' => 'success'],
            CourseAvailability::create([
                'coursecode' => $request['coursecode'],
                'semsyid' => $request['semsyid'],
                'lab' => $request['lab'],
                'time' => $request['time'],
                'section' => $request['section'],
                'section_limit' => $request['section_limit'],
                'days' => $request['days'],
            ]),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $record = CourseAvailability::where('caid', $id)
            ->with(['semester_sy'=> function($query){
                $query->with('school_year');
                $query->with('semester');
            }])
            ->first();

        if($record){
            return response()->json([
                ['status' => 'success'],
                $record
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
            'semsyid' => ['required', 'integer'],
            'time' => ['required', 'string'],
            'section' => ['required', 'string'],
            'section_limit' => ['required', 'integer'],
            'days' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $record = CourseAvailability::where('caid', $id)
            ->with(['semester_sy'=> function($query){
                $query->with('school_year');
                $query->with('semester');
            }])->first();

        if($record){
            $existingRecord = CourseAvailability::where('coursecode', $request['coursecode'])
                ->where('semsyid', $request['semsyid'])
                ->where('time', $request['time'])
                ->where('section', $request['section'])
                ->where('days', $request['days'])
                ->first();

            if($existingRecord && $existingRecord->caid != $record->caid) return response()
                ->json([
                    ['status' => 'duplicate'],
                ], 409);

            return response()->json([
                ['status' => 'success'],
                $record->update([
                    'semsyid' => $request['semsyid'],
                    'time' => $request['time'],
                    'section' => $request['section'],
                    'section_limit' => $request['section_limit'],
                    'days' => $request['days'],
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
        $currentRecord = CourseAvailability::where('caid', $id)
            ->with('enlistment_subjects')
            ->first();

        if($currentRecord) {

            //TODO: check for enlistment
            $deletable = true;
            $messages = [];
            if ($currentRecord->enlistment_subjects()->count() > 0) {
                $messages['course_availability'] = "Course is currently enlisted to students";
                $deletable = false;
            }

            if(!$deletable) return response()->json([
                ['status', 'bad request'],
                $messages
            ], 400);

            return response()->json([
                ['status'=> "deleted successfully."],
                $currentRecord->delete(),
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
        ], 404);
    }

    public function latestAvailabilityForStudent($srid)
    {

        $currentsemsy = SemSy::orderBy('semsyid', 'desc')->first();
        $currentsem = $currentsemsy->semid ==  1 ? "Sem 1" : ($currentsemsy->semid ==  2 ? "Sem 2" : "Sem 3");
        $studentRecord = StudentRecord::where('student_no', $srid)
            ->with(['curriculum' => function ($query){
                $query->with('curriculum_subjects');
            }])
            ->with(['subjects_taken' => function ($query){
                $query->where('grade', '!=', "w");
                $query->where('grade', '!=', "x");
                $query->where('grade', '!=', "5");

            }])->first();
        $cav = CourseAvailability::whereHas('semester_sy', function($query) use($currentsemsy){
            $query->where('semsyid', $currentsemsy->semsyid);
        })->whereNotIn('coursecode', $studentRecord->subjects_taken->pluck('coursecode')->toArray())
        ->whereIn('coursecode', $studentRecord->curriculum->curriculum_subjects->pluck('coursecode')->toArray())
            // ->whereHas('subjects', function($query) use($studentRecord){
            //     $query->whereNotIn('subjectid', );
            //     $query->whereHas('curriculumsubjects', function($query) use($studentRecord){
            //         $query->where('cid', $studentRecord->cid);
            //     });
            //     // commented this out to allow irregular with not taken pre-requisite to take left subjects
            //     // $query->whereHas('pre_requisites', function($query) use($studentRecord){
            //     //     $query->where('year_level_id', null)
            //     //         ->orWhere('year_level_id', "<=", $studentRecord->year_level_id)
            //     //         ->orWhere(function($query) use($studentRecord){
            //     //             $query->whereNotIn('subjectid', $studentRecord->subjects_taken->pluck('subjectid')->toArray());
            //     //         })
            //     //         ->orWhere(function($query) use($studentRecord){
            //     //             //check if studentrecord subject taken is passed or not
            //     //             $query->whereIn('subjectid', $studentRecord->subjects_taken->pluck('subjectid')->toArray())
            //     //                 ->whereHas('subjects', function($query) use($studentRecord){
            //     //                     $query->whereHas('subjectsTaken', function($query) use($studentRecord){
            //     //                         $query->whereIn('subjectid', $studentRecord->subjects_taken->pluck('subjectid'))
            //     //                             ->where('grade', "!=", null)
            //     //                             ->orWhere('grade', ">=", 3);
            //     //                     });
            //     //                 });
            //     //         });

            //     // });
        ->with(['semester_sy'=> function($query){
            $query->with('school_year');
            $query->with('semester');
        }])->get();

        $filteredCav = [];
        foreach($cav as $c){
            if($c->section_limit != 0){
                $enlisted = EnlistmentSubjects::where('caid', $c->caid)->count();
                if($enlisted < $c->section_limit){
                    $filteredCav[] = $c;
                }
            }else{
                $filteredCav[] = $c;
            }
        }

        return response()->json([
            ['status' => 'success'],
            $filteredCav
        ], 200);
    }
}
