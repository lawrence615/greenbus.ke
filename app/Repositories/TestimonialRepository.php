<?php

namespace App\Repositories;

use App\Interfaces\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Testimonial::query()->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('author_name', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('author_location', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getActive(): Collection
    {
        return Testimonial::active()->ordered()->get();
    }

    public function find(int $id): ?Testimonial
    {
        return Testimonial::find($id);
    }

    public function store(array $data): Testimonial
    {
        return Testimonial::create($data);
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($data);
        return $testimonial->fresh();
    }

    public function delete(Testimonial $testimonial): void
    {
        $testimonial->delete();
    }

    public function toggleStatus(Testimonial $testimonial): Testimonial
    {
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();
        return $testimonial;
    }
}
