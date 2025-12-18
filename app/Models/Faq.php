<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQ extends Model
{
    protected $table = 'faqs';
    
    protected $fillable = [
        'question',
        'answer',
        'category',
        'tour_category_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function tourCategory(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    public function scopeByCategory($query, ?string $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeByTourCategory($query, ?int $tourCategoryId)
    {
        if ($tourCategoryId) {
            return $query->where('tour_category_id', $tourCategoryId);
        }
        return $query;
    }
}
