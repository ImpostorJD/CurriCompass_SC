<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $primaryKey = 'cid';
    protected $fillables = [
        'programid',
    ];

    public function program(){
        return $this->belongsTo(Programs::class, 'programid', 'programid');
    }

    public function student_record(){
        return $this->hasMany(StudentRecord::class, 'cid','cid');
    }

    public function curriculum_subjects()
    {
        return $this->hasMany(CurriculumSubjects::class);
    }
}
