<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectsTaken extends Model
{
    use HasFactory;

    public $table = "subjects_taken";
    public $incrementing = false;
    protected $primaryKey = ['srid', 'subjectid'];
    protected $fillable = [
        'srid',
        'subjectid',
        'taken_at',
        'grade',
        'sy',
        'remark'
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('srid', '=', $this->getAttribute('srid'))
            ->where('subjectid', '=', $this->getAttribute('subjectid'));

        return $query;
    }

    public function studentrecord(){
        return $this->belongsTo(StudentRecord::class, 'srid', 'srid');
    }

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }

    public function school_year() {
        return $this->belongsTo(SchoolYear::class, 'sy', 'sy');
    }


}
