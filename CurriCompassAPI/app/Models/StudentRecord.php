<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'srid';

    protected $fillable = [
        'year_level',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
