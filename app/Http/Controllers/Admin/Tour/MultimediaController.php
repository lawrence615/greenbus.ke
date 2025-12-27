<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\MultimediaRepositoryInterface;
use App\Models\Tour;
use App\Http\Requests\Tour\Multimedia\DestroyRequest;
use App\Http\Requests\Tour\Multimedia\SetCoverRequest;
use App\Http\Requests\Tour\Multimedia\UpdateRequest;
use App\Http\Requests\Tour\Multimedia\UploadRequest;

class MultimediaController extends Controller
{
    public function __construct(private readonly MultimediaRepositoryInterface $multimediaRepository) {}

    /**
     * Display the multimedia management page for a tour.
     */
    public function index(Tour $tour)
    {
        $images = $this->multimediaRepository->list($tour);
        
        return view('admin.tours.multimedia.index', compact('tour', 'images'));
    }

    /**
     * Upload images for a tour.
     */
    public function upload(UploadRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($tour, $validated) {
                $images = $validated['images'];                
                $this->multimediaRepository->upload($tour, $images);
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
    public function update(UpdateRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($tour, $validated) {
                $this->multimediaRepository->updateMeta($tour, $validated['images']);
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
    public function destroy(DestroyRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($tour, $validated) {
                $this->multimediaRepository->deleteImage($tour, $validated['image_id']);
            });

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
    public function setCover(SetCoverRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($tour, $validated) {
                $this->multimediaRepository->setCover($tour, $validated['image_id']);
            });

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
