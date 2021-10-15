<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    use HasFactory;

    protected $guarded;
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function student()
    {
        return $this->belongsTo(TeStudentt::class);
    }
}
