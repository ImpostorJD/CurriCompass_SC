<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'srid';

    protected $fillable = [
        'userid',
        'year_level',
        'status',
        'student_no',
        'cid'
    ];

    public function subjects_taken()
    {
        return $this->hasMany(subjectsTaken::class, 'srid', 'srid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }

    public function curriculum(){
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }
}
