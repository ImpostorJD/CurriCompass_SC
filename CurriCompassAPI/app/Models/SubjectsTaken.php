<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectsTaken extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'srid',
        'subjectid',
        'taken_at',
        'school_year',
        'remark'
    ];

    public function studentrecord(){
        return $this->belongsTo(StudentRecord::class, 'srid', 'srid');
    }

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }

    // public function semesters()
    // {
    //     return $this->belongsTo(Semesters::class, 'taken_at', 'semid');
    // }
}
