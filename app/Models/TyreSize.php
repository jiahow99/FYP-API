<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TyreSize extends Model
{
    use HasFactory;

    protected $fillable = ['inch', 'width'];

    public function parts(): BelongsToMany {
        return $this->belongsToMany(Part::class, 'part_tyre_size', 'size_id', 'part_id')->withPivot('stock');
    } 
}
