<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pre_Requisites extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'prid';
    protected $fillable = [
        'subjectid',
        'year_level_id',
    ];

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }

    public function pre_requisites_subjects(){
        return $this->hasMany(Pre_Requisites_Subjects::class, 'prid', 'prid');
    }

    public function year_level(){
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'year_level_id');
    }
}
