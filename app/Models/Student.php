<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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

    public function user()
    {
        return $this->hasOne(User::class);
    }
    /**
     * The materialworks that belong to the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materialworks()
    {
        return $this->belongsToMany(Materialwork::class);
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
