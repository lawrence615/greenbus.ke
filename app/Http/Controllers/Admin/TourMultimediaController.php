<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\TourImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TourMultimediaController extends Controller
{
    protected $imageService;

    public function __construct(TourImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display the multimedia management page for a tour.
     */
    public function index(Tour $tour)
    {
        $images = $tour->images()->orderBy('sort_order')->get();
        
        return view('admin.tours.multimedia.index', compact('tour', 'images'));
    }

    /**
     * Upload images for a tour.
     */
    public function upload(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cover_image_index' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($tour, $validated) {
                $images = $validated['images'];
                $coverIndex = $validated['cover_image_index'] ?? 0;
                
                $this->imageService->uploadImages($tour, $images, $coverIndex);
            });

            return redirect()
                ->route('console.tours.multimedia.index', $tour)
                ->with('success', 'Images uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to upload tour images', [
                'tour_id' => $tour->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to upload images. Please try again.');
        }
    }

    /**
     * Update image details (caption, sort order).
     */
    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'images' => ['required', 'array'],
            'images.*.id' => ['required', 'integer', 'exists:tour_images,id'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'images.*.sort_order' => ['required', 'integer', 'min:0'],
            'cover_image_index' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($tour, $validated) {
                // Update image details
                foreach ($validated['images'] as $imageData) {
                    $tour->images()->where('id', $imageData['id'])->update([
                        'caption' => $imageData['caption'] ?? null,
                        'sort_order' => $imageData['sort_order'],
                    ]);
                }

                // Update cover image if specified
                if (isset($validated['cover_image_index'])) {
                    $tour->update(['cover_image_index' => $validated['cover_image_index']]);
                }
            });

            return redirect()
                ->route('console.tours.multimedia.index', $tour)
                ->with('success', 'Images updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update tour images', [
                'tour_id' => $tour->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update images. Please try again.');
        }
    }

    /**
     * Delete an image.
     */
    public function destroy(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'image_id' => ['required', 'integer', 'exists:tour_images,id'],
        ]);

        try {
            $image = $tour->images()->findOrFail($validated['image_id']);
            
            // Delete the image file and database record using deleteImages method
            $this->imageService->deleteImages($tour, [$validated['image_id']]);
            
            // If this was the cover image, update the tour
            if ($tour->cover_image_index > 0) {
                $tour->decrement('cover_image_index');
            }

            return redirect()
                ->route('console.tours.multimedia.index', $tour)
                ->with('success', 'Image deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete tour image', [
                'tour_id' => $tour->id,
                'image_id' => $validated['image_id'],
                'error' => $e->getMessage()
            ]);

            return back()
                ->with('error', 'Failed to delete image. Please try again.');
        }
    }

    /**
     * Set cover image.
     */
    public function setCover(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'image_id' => ['required', 'integer', 'exists:tour_images,id'],
        ]);

        try {
            $image = $tour->images()->findOrFail($validated['image_id']);
            
            // Reset all other images to is_cover = 0
            $tour->images()->where('is_cover', 1)->update(['is_cover' => 0]);
            
            // Set the selected image as cover
            $image->update(['is_cover' => 1]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to set cover image', [
                'tour_id' => $tour->id,
                'image_id' => $validated['image_id'],
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false], 500);
        }
    }
}
