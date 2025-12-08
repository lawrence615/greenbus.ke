<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourImageService
{
    protected string $disk = 'do';
    protected string $directory = 'tours';

    /**
     * Upload images for a tour.
     */
    public function uploadImages(Tour $tour, array $images, ?int $coverIndex = null): void
    {
        $existingCount = $tour->images()->count();

        foreach ($images as $index => $image) {
            if (!$image instanceof UploadedFile) {
                continue;
            }

            $path = $this->storeImage($image, $tour);
            $isCover = $coverIndex !== null && $index === $coverIndex;

            // If this is the cover, unset any existing cover
            if ($isCover) {
                $tour->images()->update(['is_cover' => false]);
            }

            $tour->images()->create([
                'path' => $path,
                'is_cover' => $isCover,
                'sort_order' => $existingCount + $index,
            ]);
        }
    }

    /**
     * Store a single image and return the path.
     */
    protected function storeImage(UploadedFile $image, Tour $tour): string
    {
        $filename = Str::slug($tour->title) . '-' . Str::random(8) . '.' . $image->getClientOriginalExtension();
        $path = "{$this->directory}/{$tour->id}/{$filename}";

        Storage::disk($this->disk)->put($path, file_get_contents($image), 'public');

        return $path;
    }

    /**
     * Set a specific image as the cover.
     */
    public function setCoverImage(Tour $tour, int $imageId): void
    {
        $tour->images()->update(['is_cover' => false]);
        $tour->images()->where('id', $imageId)->update(['is_cover' => true]);
    }

    /**
     * Delete specific images.
     */
    public function deleteImages(Tour $tour, array $imageIds): void
    {
        $images = $tour->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            Storage::disk($this->disk)->delete($image->path);
            $image->delete();
        }
    }

    /**
     * Delete all images for a tour.
     */
    public function deleteAllImages(Tour $tour): void
    {
        foreach ($tour->images as $image) {
            Storage::disk($this->disk)->delete($image->path);
        }

        $tour->images()->delete();

        // Also delete the tour's directory
        Storage::disk($this->disk)->deleteDirectory("{$this->directory}/{$tour->id}");
    }
}
