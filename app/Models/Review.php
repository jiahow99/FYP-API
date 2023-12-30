<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'rating',
        'part_id',
        'user_id',
    ];

    /**
     * Belongs to one Store
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }
}
