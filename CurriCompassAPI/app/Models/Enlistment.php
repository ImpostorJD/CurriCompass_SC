<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enlistment extends Model
{
    use HasFactory;
    protected $primaryKey = 'peid';
    public $timestamps = false;

    public $fillable = [
        'srid',
        'cid',
        'year_level_id',
        'semsyid'
    ];

    public function student_record(){
        return $this->belongsTo(StudentRecord::class, 'srid', 'srid');
    }

    public function year_level(){
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'year_level_id');
    }

    public function enlistment_subjects(){
        return $this->hasMany(EnlistmentSubjects::class, 'peid', 'peid');
    }

    public function curriculum(){
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }
    public function semsy(){
        return $this->belongsTo(SemSy::class, 'semsyid', 'semsyid');
    }
}
