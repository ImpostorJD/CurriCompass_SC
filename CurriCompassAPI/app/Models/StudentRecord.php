<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'srid';

    protected $fillable = [
        'student_no',
        'userid',
        'year_level_id',
        'status',
        'cid',
        'sy'
    ];

    public function enlistment(){
        return $this->hasMany(Enlistment::class, 'srid', 'srid');
    }

    public function subjects_taken() {
        return $this->hasMany(subjectsTaken::class, 'srid', 'srid');
    }

    public function user() {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }

    public function curriculum() {
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }

    public function school_year() {
        return $this->belongsTo(SchoolYear::class, 'sy', 'sy');
    }

    public function year_level(){
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'year_level_id');
    }
}
