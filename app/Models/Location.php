<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'country',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Location types
     */
    const TYPE_CITY = 'location';
    const TYPE_NATIONAL_PARK = 'national_park';
    const TYPE_RESERVE = 'reserve';
    const TYPE_REGION = 'region';
    const TYPE_MOUNTAIN = 'mountain';
    const TYPE_LAKE = 'lake';
    const TYPE_BEACH = 'beach';
    const TYPE_AREA = 'area';

    protected static function booted(): void
    {
        static::creating(function (Location $location) {
            if (empty($location->slug) && !empty($location->name)) {
                $location->slug = static::generateUniqueSlug($location->name);
            }
        });

        static::created(function (Location $location) {
            if (empty($location->slug) && !empty($location->name)) {
                $location->slug = static::generateUniqueSlug($location->name);
                $location->saveQuietly();
            }
        });

        static::updating(function (Location $location) {
            if ($location->isDirty('name') && empty($location->slug) && !empty($location->name)) {
                $location->slug = static::generateUniqueSlug($location->name);
            }
        });
    }

    /**
     * Generate a unique slug from the given name
     */
    public static function generateUniqueSlug(string $name): string
    {
        $slug = str($name)->slug()->toString();
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get all tours for this location
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * Get display name with type
     */
    public function getDisplayNameAttribute(): string
    {
        $typeLabel = $this->getTypeLabel();
        return $this->name . ($typeLabel ? " ({$typeLabel})" : '');
    }

    /**
     * Get human-readable type label
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            self::TYPE_CITY => 'Location',
            self::TYPE_NATIONAL_PARK => 'National Park',
            self::TYPE_RESERVE => 'Reserve',
            self::TYPE_REGION => 'Region',
            self::TYPE_MOUNTAIN => 'Mountain',
            self::TYPE_LAKE => 'Lake',
            self::TYPE_BEACH => 'Beach',
            self::TYPE_AREA => 'Area',
            default => ''
        };
    }

    /**
     * Scope to get only active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get locations by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
