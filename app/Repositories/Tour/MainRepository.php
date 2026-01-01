<?php

namespace App\Repositories\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Interfaces\Tour\MainRepositoryInterface;
use App\Models\Location;
use App\Models\Tour;

class MainRepository implements MainRepositoryInterface
{
    /**
     * Get all tours for guest with filters
     */
    public function guestIndex(Location $location, int $perPage = 6, ?string $search = null)
    {
        $query = $location->tours()
            ->where('status', 'published')
            ->with(['images', 'category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all tours for admin with filters
     */
    public function adminIndex(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Tour::with(['location', 'category', 'images'])->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('tour_category_id', $filters['category_id']);
        }

        if (!empty($filters['is_the_bus_tour'])) {
            $query->where('is_the_bus_tour', true);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get a tour by ID with relationships
     */
    public function get(Location $location, Tour $tour)
    {
        if ($tour->location_id !== $location->id || $tour->status !== 'published') {
            abort(404);
        }

        return $tour->load(['images', 'category', 'itineraryItems']);
    }

    public function delete(Tour $tour): void
    {
        $tour->delete();
    }

    /**
     * Get only trashed tours
     */
    public function trashed(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Tour::onlyTrashed()
            ->with(['location', 'category', 'images'])
            ->latest('deleted_at');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('tour_category_id', $filters['category_id']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Restore a soft deleted tour
     */
    public function restore(Tour $tour): void
    {
        $tour->restore();
    }

    /**
     * Permanently delete a tour
     */
    public function forceDelete(Tour $tour): void
    {
        $tour->forceDelete();
    }

    /**
     * Toggle tour status
     */
    public function toggleStatus(Tour $tour): Tour
    {
        $tour->status = $tour->status === 'published' ? 'draft' : 'published';
        $tour->save();
        return $tour;
    }

    /**
     * Get the bus tour (tour marked as is_the_bus_tour)
     */
    public function getBusTour(): ?Tour
    {
        return Tour::where('is_the_bus_tour', true)
            ->where('status', 'published')
            // ->with(['images', 'category'])
            ->first();
    }
}
