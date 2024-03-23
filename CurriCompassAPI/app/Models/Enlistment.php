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
        'coid'
    ];

    public function consultation(){
        return $this->belongsTo(Consultation::class, 'coid', 'coid');
    }

    public function student_record(){
        return $this->belongsTo(StudentRecord::class, 'studentid', 'studentid');
    }
}
