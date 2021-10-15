<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonTeacher extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'lesson_teacher';
    
    /**
     * Get the classrooms that owns the LessonTeacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    /**
     * Get the classrooms that owns the LessonTeacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    /**
     * Get the classrooms that owns the LessonTeacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(lesson::class);
    }

    /**
     * Get the classrooms that owns the LessonTeacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
