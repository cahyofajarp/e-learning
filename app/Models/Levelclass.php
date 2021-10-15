<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Levelclass extends Model
{
    use HasFactory;

    protected $guarded= [];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * Get all of the classrooms for the Levelclass
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
