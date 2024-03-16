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
        'year_level',
        'completion',
    ];

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }

    public function pre_requisites_subjects(){
        return $this->hasMany(Pre_Requisites_Subjects::class, 'prid', 'prid');
    }
}
