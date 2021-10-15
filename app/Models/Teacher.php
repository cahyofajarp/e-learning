<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function lessons()
    {
        return $this->belongsToMany(lesson::class)->withPivot('classroom_id')->withTimestamps();
    }
}
