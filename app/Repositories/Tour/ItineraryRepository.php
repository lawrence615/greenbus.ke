<?php

namespace App\Repositories\Tour;

use Illuminate\Support\Collection;

use App\Interfaces\Tour\ItineraryRepositoryInterface;
use App\Models\Tour;
use App\Models\TourItineraryItem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItineraryRepository implements ItineraryRepositoryInterface
{
    public function list(Tour $tour): Collection
    {
        return $tour->itineraryItems()->orderBy('sort_order')->get();
    }

    public function create(Tour $tour, array $data): TourItineraryItem
    {
        $maxSortOrder = $tour->itineraryItems()->max('sort_order') ?? 0;

        return $tour->itineraryItems()->create([
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration_value' => $data['duration_value'] ?? null,
            'duration_unit' => $data['duration_unit'] ?? 'minutes',
            'sort_order' => $maxSortOrder + 1,
        ]);
    }

    public function update(TourItineraryItem $itineraryItem, array $data): void
    {
        $itineraryItem->update([
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration_value' => $data['duration_value'] ?? null,
            'duration_unit' => $data['duration_unit'] ?? 'minutes',
        ]);
    }

    public function delete(TourItineraryItem $itineraryItem): void
    {
        $itineraryItem->delete();
    }

    public function reorder(Tour $tour, array $itemIds): void
    {
        $count = $tour->itineraryItems()->whereIn('id', $itemIds)->count();

        if ($count !== count($itemIds)) {
            throw new NotFoundHttpException();
        }

        foreach ($itemIds as $sortOrder => $itemId) {
            $tour->itineraryItems()
                ->where('id', $itemId)
                ->update(['sort_order' => $sortOrder]);
        }
    }

    public function assertBelongsToTour(Tour $tour, TourItineraryItem $itineraryItem): void
    {
        if ($itineraryItem->tour_id !== $tour->id) {
            throw new NotFoundHttpException();
        }
    }
}
