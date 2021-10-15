<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answerwork extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the materialwork that owns the Answerwork
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function materialwork()
    {
        return $this->belongsTo(Materialwork::class);
    }
}
