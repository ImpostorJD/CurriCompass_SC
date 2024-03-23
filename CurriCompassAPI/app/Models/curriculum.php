<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $primaryKey = 'cid';
    protected $fillable = [
        'programid',
        'specialization',
        'sy',
    ];

    public function consultations(){
        return $this->hasMany(Consultation::class, 'cid', 'cid');
    }

    public function program(){
        return $this->belongsTo(Programs::class, 'programid', 'programid');
    }

    public function student_record(){
        return $this->hasMany(StudentRecord::class, 'cid','cid');
    }

    public function curriculum_subjects(){
        return $this->hasMany(CurriculumSubjects::class, 'cid', 'cid');
    }

    public function school_year(){
        return $this->belongsTo(SchoolYear::class, 'sy', 'sy');
    }
}
