<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory; 
    
    protected $guarded;
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the student that owns the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }  
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
