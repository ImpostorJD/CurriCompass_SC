<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;

    protected $primaryKey = 'subjectid';
    protected $fillable =[
        'subjectname',
        'subjectcode',
        'subjectcredits'
    ];

    public function subjectsTaken()
    {
        return $this->hasMany(subjectsTaken::class);
    }

    public function curriculumsubjects()
    {
        return $this->hasMany(CurriculumSubjects::class);
    }

}
