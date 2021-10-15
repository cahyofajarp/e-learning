<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * The classrooms that belong to the Test
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withPivot('lesson_id')->withTimestamps();
    }
/**
     * The teacher that belong to the Test
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get all of the results for the Test
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    

    
}
