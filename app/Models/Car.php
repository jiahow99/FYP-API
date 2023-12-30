<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'mileage',
        'yearMake',
    ];

    /**
     * Belongs to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Has many Parts
     */
    public function parts()
    {
        return $this->belongsToMany(Part::class, 'car_part');
    }

    /**
     * Has one Image
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
