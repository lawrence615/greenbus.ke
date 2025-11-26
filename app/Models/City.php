<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Tour;

class City extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'country',
        'is_active',
    ];

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }
}
