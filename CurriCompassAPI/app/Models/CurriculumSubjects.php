<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumSubjects extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['cid', 'coursecode'];
    protected $fillable = [
        'cid',
        'coursecode',
        'coursedescription',
        'prerequisites',
        'units',
        'unitslab',
        'unitslec',
        'hourslab',
        'hourslec',
        'semid',
        'year_level_id',
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('cid', '=', $this->getAttribute('cid'))
            ->where('coursecode', '=', $this->getAttribute('coursecode'));

        return $query;
    }

    public function curricula(){
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }


    public function semesters(){
        return $this->belongsTo(Semesters::class, 'semid', 'semid');
    }

    public function year_level(){
        return $this->belongsTo(YearLevel::class, 'year_level_id',  'year_level_id');
    }
}
