<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAvailability extends Model
{
    use HasFactory;

    protected $primaryKey = ['subjectid','semid'];
    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'semid',
        'subjectid'
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('semid', '=', $this->getAttribute('semid'))
            ->where('subjectid', '=', $this->getAttribute('subjectid'));

        return $query;
    }

    public function semesters(){
        return $this->belongsTo(Semesters::class, 'semid', 'semid');
    }

    public function subjects() {
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }
}
