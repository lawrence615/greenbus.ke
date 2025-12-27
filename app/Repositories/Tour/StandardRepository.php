<?php

namespace App\Repositories\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

use App\Interfaces\Tour\StandardRepositoryInterface;
use App\Models\Tour;

class StandardRepository implements StandardRepositoryInterface
{
    public function index(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Tour::with(['location', 'category', 'images'])
            ->where('tour_type', 'standard')
            ->latest();

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

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Tour
    {
        return Tour::with(['location', 'category', 'images', 'itineraryItems'])
            ->where('tour_type', 'standard')
            ->firstOrFail($id);
    }

    public function findBySlug(string $slug): ?Tour
    {
        return Tour::with(['location', 'category', 'images'])
            ->where('tour_type', 'standard')
            ->where('slug', $slug)
            ->firstOrFail();
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
}
