<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lesson extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * The teachers that belong to the lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class)->withPivot('classroom_id')->withTimestamps();
    }

    /**
     * Get all of the tests for the lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function works()
    {
        return $this->hasMany(Work::class);
    }

    /**
     * Get all of the tests for the lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

}
