<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $primaryKey = 'coid';

    protected $fillable = [
        'sy',
        'cid',
        'year_level',
        'srid'
    ];

    public function enilstment(){
        return $this->hasMany(Enlistment::class, 'coid', 'coid');
    }

    public function consultation_subjects(){
        return $this->hasMany(ConsultationSubjects::class, 'coid', 'coid');
    }

    public function student_record(){
        return $this->belongsTo(StudentRecord::class, 'srid', 'srid');
    }

    public function curriculum(){
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }

    public function semesters(){
        return $this->belongsTo(Semesters::class, 'semid', 'semid');
    }

    public function school_year(){
        return $this->belongsTo(SchoolYear::class, 'sy', 'sy');
    }
}
