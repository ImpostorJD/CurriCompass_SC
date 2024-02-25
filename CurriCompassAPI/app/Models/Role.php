<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'roleid';
    protected $fillable = [
        'rolename'
    ];
    protected $hidden = [];

    public function User_Roles(){
        return $this->belongsToMany(User::class, 'user__roles', 'roleid', 'userid');
    }
}
