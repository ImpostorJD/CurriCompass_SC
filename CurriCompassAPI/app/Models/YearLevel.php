<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'year_level_id';

    protected $fillable = [
        'year_level_desc'
    ];
}
