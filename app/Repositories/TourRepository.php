<?php

namespace App\Repositories;

use App\Interfaces\TourRepositoryInterface;
use App\Models\City;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TourRepository implements TourRepositoryInterface
{
    public function index(City $city, int $perPage = 12, ?string $search = null)
    {
        $query = $city->tours()
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

    public function get(City $city, Tour $tour)
    {
        if ($tour->city_id !== $city->id || $tour->status !== 'published') {
            abort(404);
        }

        return $tour->load(['images', 'category', 'itineraryItems']);
    }

    public function adminIndex(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Tour::with(['city', 'category', 'images'])->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('tour_category_id', $filters['category_id']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Tour
    {
        return Tour::with(['city', 'category', 'images', 'itineraryItems'])->find($id);
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
        return $tour->fresh(['city', 'category', 'images']);
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
