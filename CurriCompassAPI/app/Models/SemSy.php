<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemSy extends Model
{
    use HasFactory;

    public $timestamp = false;
    protected $primaryKey = 'semsyid';
    protected $fillable = [
        'semid',
        'sy'
    ];
}
