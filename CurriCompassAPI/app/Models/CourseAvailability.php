<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAvailability extends Model
{
    use HasFactory;

    protected $primaryKey = 'caid';
    public $timestamps = false;


    protected $fillable = [
        'subjectid',
        'time',
        'semsyid',
        'section',
        'section_limit',
        'days'
    ];

    // protected function setKeysForSaveQuery($query)
    // {
    //     $query
    //         ->where('semid', '=', $this->getAttribute('semid'))
    //         ->where('subjectid', '=', $this->getAttribute('subjectid'));

    //     return $query;
    // }

    public function enlistment_subjects(){
        return $this->hasMany(EnlistmentSubjects::class, 'caid', 'caid');
    }
    public function semester_sy(){
        return $this->belongsTo(SemSy::class, 'semsyid', 'semsyid');
    }

    public function subjects() {
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }
}
