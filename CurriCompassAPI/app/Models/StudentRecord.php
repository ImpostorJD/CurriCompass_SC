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
        'studentno',
        'cid'
    ];

    public function subjectsTaken()
    {
        return $this->hasMany(subjectsTaken::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }

    public function curriculum(){
        return $this->belongsTo(Curriculum::class, 'cid', 'cid');
    }
}
