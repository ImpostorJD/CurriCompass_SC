<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semesters extends Model
{
    use HasFactory;

    protected $primaryKey = 'semid';

    protected $fillables = [
        'semdesc'
    ];

    public function subjects_taken()
    {
        return $this->hasOne(SubjectsTaken::class, 'taken_at', 'semid');
    }

    public function curriculum_subjects(){
        return $this->hasMany(CurriculumSubjects::class, 'semid', 'semid');
    }
}
