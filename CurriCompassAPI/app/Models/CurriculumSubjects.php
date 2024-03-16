<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumSubjects extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillables = [
        'cid',
        'subjectid',
        'semid',
        'year_level',
    ];

    public function curricula(){
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }

    public function semesters(){
        return $this->belongsTo(Semesters::class, 'semid', 'semid');
    }

}
