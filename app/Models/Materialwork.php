<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materialwork extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The students that belong to the Materialwork
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }

    /**
     * Get the work that owns the Materialwork
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Get all of the answerworks for the Materialwork
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answerwork()
    {
        return $this->hasOne(Answerwork::class);
    }
}
