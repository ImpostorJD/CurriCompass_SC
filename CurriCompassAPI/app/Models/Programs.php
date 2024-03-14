<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'programid';
    protected $fillable = [
        'programcode',
        'programdesc'
    ];

    public function curriculum() {
        return $this->hasOne(curriculum::class, 'programid', 'programid');
    }
}
