<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnlistmentSubjects extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = ['peid', 'caid'];
    public $timestamps = false;
    protected $fillable = [
        'peid',
        'caid',
    ];
    public function setKeysForSaveQuery($query)
    {
        return $query->where('peid', $this->attributes['peid'])
                    ->where('caid', $this->attributes['caid']);
    }

    public function enlistment(){
        return $this->belongsTo(Enlistment::class, 'peid', 'peid');
    }

    public function course_availability(){
        return $this->belongsTo(CourseAvailability::class, 'caid', 'caid');
    }
}
