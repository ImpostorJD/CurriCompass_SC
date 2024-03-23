<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pre_Requisites_Subjects extends Model
{
    use HasFactory;

    protected $primaryKey = ['subjectid', 'prid'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'subjectid',
        'prid',
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('prid', '=', $this->getAttribute('prid'))
            ->where('subjectid', '=', $this->getAttribute('subjectid'));

        return $query;
    }
    public function pre_requisites(){
        return $this->belongsTo(Pre_Requisites::class, 'prid', 'prid');
    }

    public function subjects(){
        return $this->belongsTo(Subjects::class, 'subjectid', 'subjectid');
    }
}
