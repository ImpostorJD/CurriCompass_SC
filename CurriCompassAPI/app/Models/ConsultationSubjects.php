<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationSubjects extends Model
{
    use HasFactory;

    protected $primaryKey = ['coid', 'subjectid'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'coid',
        'subjectid'
    ];
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('coid', '=', $this->getAttribute('coid'))
            ->where('subjectid', '=', $this->getAttribute('subjectid'));

        return $query;
    }

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjecid');
    }

    public function consultation(){
        return $this->belongsTo(Consultation::class, 'coid', 'coid');
    }
}
