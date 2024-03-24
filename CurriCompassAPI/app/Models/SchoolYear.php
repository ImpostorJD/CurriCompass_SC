<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;
    protected $primaryKey = 'sy';
    protected $fillable = [
        'sy_start',
        'sy_end',
    ];

    public function equals(SchoolYear $other)
    {
        return $this->sy_start === $other->sy_start && $this->sy_end === $other->sy_end;
    }

    public function consultations(){
        return $this->hasMany(Consultation::class, 'semid', 'semid');
    }

    public function student_records() {
        return $this->hasMany(StudentRecord::class, 'sy', 'sy');
    }

    public function subjects_taken() {
        return $this->hasMany(SubjectsTaken::class, 'sy', 'sy');
    }

    public function curriculum() {
        return $this->hasMany(Curriculum::class, 'sy', 'sy');
    }
}
