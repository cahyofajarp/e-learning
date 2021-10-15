<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    // public function getRouteKeyName()
    // {   
    //     return str_replace(' ','-',$this->title);
    // }
    
    public function fileworks()
    {
        return $this->hasMany(Filework::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function lesson()
    {
        return $this->belongsTo(lesson::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withTimestamps();
    }
    
    public function materialworks()
    {
        return $this->hasMany(Materialwork::class);
    }
}
