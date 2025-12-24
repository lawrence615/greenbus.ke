<?php

namespace App\Repositories\Tour;

use App\Interfaces\Tour\BespokeRepositoryInterface;
use Illuminate\Support\Str;

use App\Models\Tour;

class BespokeRepository implements BespokeRepositoryInterface
{

    public function store(array $data): Tour
    {
        // Create slug
        $data['slug'] = Str::slug($data['title']);

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Tour::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        return Tour::create($data);
    }

    public function findBySlug(string $slug): ?Tour
    {
        return Tour::with(['location', 'category', 'images'])
            ->where('slug', $slug)
            ->first();
    }

    public function update(Tour $tour, array $data): Tour
    {
        if (isset($data['title']) && $data['title'] !== $tour->title) {
            $data['slug'] = Str::slug($data['title']);

            $originalSlug = $data['slug'];
            $counter = 1;
            while (Tour::where('slug', $data['slug'])->where('id', '!=', $tour->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        $tour->update($data);
        return $tour->fresh();
    }
}
