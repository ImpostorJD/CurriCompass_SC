<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Role extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = ['userid', 'roleid'];
    protected $fillable = [
        'userid',
        'roleid',
    ];
}
