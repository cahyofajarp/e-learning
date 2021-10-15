<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * Get the levelclass that owns the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function levelclass()
    {
        return $this->belongsTo(Levelclass::class);
    }

    /**
     * Get all of the lessons for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(lesson::class);
    }

    /**
     * Get all of the tests for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class)->withPivot('lesson_id')->withTimestamps();
    }

    /**
     * Get all of the lessonteachers for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonteachers()
    {
        return $this->hasMany(LessonTeacher::class);
    }

    /**
     * Get all of the students for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function works()
    {
        return $this->belongsToMany(Work::class)->withTimestamps();
    }

}
