<?php

namespace App\Repositories;

use App\Interfaces\FAQRepositoryInterface;
use App\Models\FAQ;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FAQRepository implements FAQRepositoryInterface
{
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = FAQ::query()->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getActive(?string $category = null): Collection
    {
        return FAQ::active()->byCategory($category)->ordered()->get();
    }

    public function getPublicFaqs(): Collection
    {
        return FAQ::active()->ordered()->get();
    }

    public function getCategories(): array
    {
        return FAQ::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    public function find(int $id): ?FAQ
    {
        return FAQ::find($id);
    }

    public function store(array $data): FAQ
    {
        return FAQ::create($data);
    }

    public function update(FAQ $faq, array $data): FAQ
    {
        $faq->update($data);
        return $faq->fresh();
    }

    public function delete(FAQ $faq): void
    {
        $faq->delete();
    }

    public function toggleStatus(FAQ $faq): FAQ
    {
        $faq->is_active = !$faq->is_active;
        $faq->save();
        return $faq;
    }
}
