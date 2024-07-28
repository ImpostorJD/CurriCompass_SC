<?php

namespace App\Http\Controllers;

use App\Imports\CurriculumImport;
use App\Models\Curriculum;
use App\Models\CurriculumSubjects;
use App\Models\StudentRecord;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

//TODO: Add documentation
class CurriculumController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index(){

        return response()->json([
            ['status' => "success"],
            Curriculum::with('program')
                ->with('school_year')
                ->get()
        ]);
    }

    public function show(Request $request, String $id){
        $curriculum = Curriculum::where('cid', $id)
            ->with('school_year')
            ->with(['curriculum_subjects'=> function($query) {
                $query->with('semesters');
                $query->with('year_level');
            }])->first();

        if ($curriculum != null) {
           return response()->json([
                ['status' => 'success'],
                $curriculum
           ], 200);
        }

        return response()->json(['status' => 'not found'], 404);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'programid' => ['required', 'integer'],
            'specialization' => ['nullable', 'string'],
            'sy' => ['required', 'integer'],
            'curriculum_subjects' => ['required', 'array'],
            'curriculum_subjects*.semester' => ['required', 'integer'],
            'curriculum_subjects*.level' => ['required', 'integer'],
            'curriculum_subjects*.subjects' => ['required', 'array'],
            'curriculum_subjects*.subjects*.coursecode' => ['required', 'string'],
            'curriculum_subjects*.subjects*.coursedescription' => ['required', 'string'],
            'curriculum_subjects*.subjects*.hourslab' => ['required', 'decimal:0'],
            'curriculum_subjects*.subjects*.hourslec' => ['required', 'decimal:0'],
            'curriculum_subjects*.subjects*.units' => ['required', 'integer'],
            'curriculum_subjects*.subjects*.unitslab' => ['required', 'integer'],
            'curriculum_subjects*.subjects*.unitslec' => ['required', 'integer'],
            'curriculum_subjects*.subjects*.prerequisites' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $existing = Curriculum::where('programid', $request->programid)
            ->where('specialization', $request->specialization)
            ->where('sy', $request->sy)
            ->first();

        if($existing != null){
            return response()->json([['status' => 'conflict'], "Combination already exists."], 409);
        }

        $curriculum = Curriculum::create([
            'programid' => $request->programid,
            'specialization' => $request->specialization,
            'sy' => $request->sy,
        ]);

        foreach ($request->curriculum_subjects as $cs){
            foreach ($cs['subjects'] as $subject){
                CurriculumSubjects::create([
                    'cid' => $curriculum->cid,
                    'coursecode' => $subject['coursecode'],
                    'coursedescription' => $subject['coursedescription'],
                    'prerequisites' => $subject['prerequisites'],
                    'units' => $subject['units'],
                    'unitslab' => $subject['unitslab'],
                    'unitslec' => $subject['unitslec'],
                    'hourslab' => $subject['hourslab'],
                    'hourslec' => $subject['hourslec'],
                    'semid' => $cs['semester'],
                    'year_level_id' => $cs['level'],
                ]);
            }
        }

        return response()->json([
            ['status' => 'success'],
            $curriculum
        ], 200);
    }

    public function bulk(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid file upload',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            // Read the file to get the sheet count
            $path = $request->file('file')->getRealPath();
            $reader = new Xlsx();
            $spreadsheet = $reader->load($path);
            $sheetCount = $spreadsheet->getSheetCount();
            // Perform the import with the correct sheet count
            Excel::import(new CurriculumImport($sheetCount), $request->file('file'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success'], 200);
    }
    public function destroy(Request $request, String $id){
        $curriculum = Curriculum::where('cid', $id)->first();

        if ($curriculum != null){
            if (StudentRecord::where('cid', $curriculum->cid)->count() > 0){
                return response()->json(['status' => 'conflict'], 409);
            }
            $curriculum->delete();
            return response()->json(['status' => 'successfully deleted'], 200);
        }
        return response()->json(['status' => 'not found'], 404);
    }

    public function update(Request $request, String $id)
    {
        $validate = Validator::make($request->all(), [
            'programid' => ['required', 'integer'],
            'specialization' => ['nullable', 'string'],
            'sy' => ['required', 'integer'],
            'curriculum_subjects' => ['required', 'array'],
            'curriculum_subjects*.semester' => ['required', 'integer'],
            'curriculum_subjects*.level' => ['required', 'integer'],
            'curriculum_subjects*.subjects' => ['required', 'array'],
            'curriculum_subjects*.subjects*.coursecode' => ['required', 'string'],
            'curriculum_subjects*.subjects*.coursedescription' => ['required', 'string'],
            'curriculum_subjects*.subjects*.hourslab' => ['required', 'decimal:0'],
            'curriculum_subjects*.subjects*.hourslec' => ['required', 'decimal:0'],
            'curriculum_subjects*.subjects*.units' => ['required', 'integer'],
            'curriculum_subjects*.subjects*.unitslab' => ['required', 'integer'],
            'curriculum_subjects*.subjects*.unitslec' => ['required', 'integer'],
            'curriculum_subjects*.subjects*.prerequisites' => ['required', 'string'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        $existing = Curriculum::where('programid', $request->programid)
            ->where('specialization', $request->specialization)
            ->where('sy', $request->sy)
            ->first();

        $curriculum = Curriculum::where('cid', $id)->first();

        if(!$curriculum){
            return response()->json(['status' => 'not found'], 404);
        }

        if($existing != null && $existing->cid != $id &&
            ($existing->programid == $request->programid &&
            $existing->specialization == $request->specialization &&
            $existing->sy == $request->sy)) {
            return response()->json([['status' => 'conflict'], "Combination already exists."], 409);
        }

        $curriculum->update([
            'programid' => $request->programid,
            'specialization' => $request->specialization,
            'sy' => $request->sy
        ]);

        $curriculum->curriculum_subjects()->delete();

        foreach ($request->curriculum_subjects as $cs){
            foreach ($cs['subjects'] as $subject){
                CurriculumSubjects::create([
                    'cid' => $curriculum->cid,
                    'coursecode' => $subject['coursecode'],
                    'coursedescription' => $subject['coursedescription'],
                    'prerequisites' => $subject['prerequisites'],
                    'units' => $subject['units'],
                    'unitslab' => $subject['unitslab'],
                    'unitslec' => $subject['unitslec'],
                    'hourslab' => $subject['hourslab'],
                    'hourslec' => $subject['hourslec'],
                    'semid' => $cs['semester'],
                    'year_level_id' => $cs['level'],
                ]);
            }
        }

        return response()->json([
            ['status' => 'success'],
            $curriculum
        ], 200);
    }
}
