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
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('userid', '=', $this->getAttribute('userid'))
            ->where('roleid', '=', $this->getAttribute('roleid'));

        return $query;
    }
}
