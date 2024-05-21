<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnlistmentSubjects extends Model
{
    use HasFactory;

    protected $primaryKey = ['peid', 'caid'];

    public function enlistment(){
        return $this->belongsTo(Enlistment::class, 'peid', 'peid');
    }

    public function course_availability(){
        return $this->belongsTo(CourseAvailability::class, 'caid', 'caid');
    }
}
