<?php

namespace App\Repositories;

use App\Interfaces\TourRepositoryInterface;
use App\Models\Location;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TourRepository implements TourRepositoryInterface
{
    public function index(Location $location, int $perPage = 12, ?string $search = null)
    {
        $query = $location->tours()
            ->where('status', 'published')
            ->where('tour_type', '!=', 'bespoke')
            ->with(['images', 'category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function get(Location $location, Tour $tour)
    {
        if ($tour->location_id !== $location->id || $tour->status !== 'published' || $tour->tour_type === 'bespoke') {
            abort(404);
        }

        return $tour->load(['images', 'category', 'itineraryItems']);
    }

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

        // if (!empty($filters['is_the_bus_tour'])) {
        //     $query->where('is_the_bus_tour', true);
        // }

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Tour
    {
        return Tour::with(['location', 'category', 'images', 'itineraryItems'])->find($id);
    }

    public function store(array $data): Tour
    {
        $data['slug'] = Str::slug($data['title']);
        
        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Tour::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        return Tour::create($data);
    }

    public function update(Tour $tour, array $data): Tour
    {
        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $tour->title) {
            $data['slug'] = Str::slug($data['title']);
            
            // Ensure unique slug (excluding current tour)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Tour::where('slug', $data['slug'])->where('id', '!=', $tour->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        $tour->update($data);
        return $tour->fresh(['location', 'category', 'images']);
    }

    public function delete(Tour $tour): void
    {
        $tour->delete();
    }

    public function toggleStatus(Tour $tour): Tour
    {
        $tour->status = $tour->status === 'published' ? 'draft' : 'published';
        $tour->save();
        return $tour;
    }
}
