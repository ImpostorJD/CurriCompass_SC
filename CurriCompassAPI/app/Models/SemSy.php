<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemSy extends Model
{
    use HasFactory;

    public $table = "sem_sy";
    public $timestamps = false;
    protected $primaryKey = 'semsyid';
    protected $fillable = [
        'semid',
        'sy'
    ];

    public function semester(){
        return $this->belongsTo(Semesters::class, 'semid','semid');
    }

    public function school_year(){
        return $this->belongsTo(SchoolYear::class, 'sy','sy');
    }

    public function course_availability(){
        return $this->hasMany(CourseAvailability::class, 'semsyid','semsyid');
    }
}

