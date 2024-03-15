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
        'subjectcredits',
        'subjecttype'
    ];

    public function subjectsTaken()
    {
        return $this->hasMany(subjectsTaken::class);
    }

    public function curriculumsubjects()
    {
        return $this->hasMany(CurriculumSubjects::class);
    }

    public function pre_requisites(){
        return $this->hasOne(Pre_Requisites::class, 'subjectid', 'subjectid');
    }

    public function pre_requisites_subjects(){
        return $this->hasMany(Pre_Requisites_Subjects::class, 'subjectid', 'subjectid');
    }
}
