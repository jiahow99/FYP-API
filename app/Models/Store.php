<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'slug',
        'address',
        'place_id',
        'uid',
        'token',
    ];

    /**
     * Has many Orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Has many Parts
     */
    public function parts()
    {
        return $this->hasMany(Part::class);
    }   

    /**
     * Has one Image
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Has one mechanic
     */
    public function mechanic()
    {
        return $this->hasOne(Mechanic::class);
    }
}
