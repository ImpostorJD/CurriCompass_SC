<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semesters extends Model
{
    use HasFactory;
    public function subjects_taken()
    {
        return $this->hasOne(SubjectsTaken::class, 'taken_at', 'semid');
    }
}
