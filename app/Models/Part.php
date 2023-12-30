<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'brand_name',
    ];

    /**
     * Has many Cars
     */
    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_part');
    }

    /**
     * Belongs to many Store
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Belongs to many Car
     */
    public function orders(): MorphToMany
    {
        return $this->morphedByMany(Order::class, 'partable');
    }

    /**
     * Belongs to one Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Has many Images
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): HasMany 
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Available inch options of "tyre"
     */
    public function availableInchs(): BelongsToMany {
        return $this->belongsToMany(TyreSize::class, 'part_tyre_size', 'part_id', 'size_id')->withPivot('stock');
    }

    /**
     * Filter by details
     */
    public function scopeFilterTyre(Builder $query, $requests)
    {
        return $query
            ->when(
                isset($requests['inch']) && !empty($requests['inch']), 
                function (Builder $query) use ($requests) {
                    $query->whereHas('availableInchs', fn($subQuery) => $subQuery->whereIn('inch', $requests->inch));
            })
            ->when(
                isset($requests['brand_name']) && !empty($requests['brand_name']), 
                function (Builder $query) use ($requests) {
                    $query->whereIn('brand_name', $requests->brand_name);
            });
    }

    /**
     * Filter spare parts by details
     */
    public function scopeFilterPart(Builder $query, $requests)
    {
        return $query
            // ->when(
            //     isset($requests['brand_name']) && !empty($requests['brand_name']), 
            //     function (Builder $query) use ($requests) {
            //         $query->whereIn('brand_name', $requests->brand_name);
            // })
            ->when(
                isset($requests['categories']) && !empty($requests['categories']), 
                function (Builder $query) use ($requests) {
                    $query->whereHas('category', fn($subQuery) => $subQuery->whereIn('slug', $requests->categories));
            });
    }
}
