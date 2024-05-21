<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'year_level_id';

    protected $fillable = [
        'year_level_desc'
    ];

    public function student_record(){
        return $this->hasMany(StudentRecord::class, 'year_level_id', 'year_level_id');
    }

    public function curriculum_subjects(){
        return $this->hasMany(CurriculumSubjects::class, 'year_level_id', 'year_level_id');
    }

    public function pre_requisites(){
        return $this->hasMany(Pre_Requisites::class, 'year_level_id', 'year_level_id');
    }

    public function enlistment(){
        return $this->hasMany(Enlistment::class, 'year_level_id', 'year_level_id');
    }
}
