<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact', 'store_id'
    ];

    public function store() 
    {
        return $this->belongsTo(Store::class);
    }
}
