<?php

namespace App\Repositories\Tour;

use Illuminate\Support\Collection;

use App\Interfaces\Tour\MultimediaRepositoryInterface;
use App\Models\Tour;
use App\Services\TourImageService;

class MultimediaRepository implements MultimediaRepositoryInterface
{
    public function __construct(private readonly TourImageService $imageService)
    {
    }

    public function list(Tour $tour): Collection
    {
        return $tour->images()->orderBy('sort_order')->get();
    }

    public function upload(Tour $tour, array $images): void
    {
        $existingCount = $tour->images()->count();

        foreach ($images as $index => $image) {
            $path = $this->imageService->store($image, $tour->code, $tour->title);

            $tour->images()->create([
                'path' => $path,
                'is_cover' => false,
                'sort_order' => $existingCount + $index,
            ]);
        }
    }

    public function updateMeta(Tour $tour, array $images): void
    {
        foreach ($images as $imageData) {
            $tour->images()->where('id', $imageData['id'])->update([
                'caption' => $imageData['caption'] ?? null,
                'sort_order' => $imageData['sort_order'],
            ]);
        }
    }

    public function deleteImage(Tour $tour, int $imageId): void
    {
        $image = $tour->images()->findOrFail($imageId);

        $this->imageService->delete($image->path);
        $image->delete();
    }

    public function setCover(Tour $tour, int $imageId): void
    {
        $tour->images()->findOrFail($imageId);

        $tour->images()->where('is_cover', 1)->update(['is_cover' => 0]);
        $tour->images()->where('id', $imageId)->update(['is_cover' => 1]);
    }
}
