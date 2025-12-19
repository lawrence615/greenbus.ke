<?php

namespace App\Repositories;

use App\Interfaces\FAQRepositoryInterface;
use App\Models\Faq;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FaqRepository implements FaqRepositoryInterface
{
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Faq::query()->latest();

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
        return Faq::active()->byCategory($category)->ordered()->get();
    }

    public function getPublicFaqs(): Collection
    {
        return Faq::active()->ordered()->get();
    }

    public function getCategories(): array
    {
        return Faq::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    public function find(int $id): ?Faq
    {
        return Faq::find($id);
    }

    public function store(array $data): Faq
    {
        return Faq::create($data);
    }

    public function update(Faq $faq, array $data): Faq
    {
        $faq->update($data);
        return $faq->fresh();
    }

    public function delete(Faq $faq): void
    {
        $faq->delete();
    }

    public function toggleStatus(Faq $faq): Faq
    {
        $faq->is_active = !$faq->is_active;
        $faq->save();
        return $faq;
    }
}
