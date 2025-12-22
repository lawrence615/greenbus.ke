<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tour\StoreRequest;
use App\Http\Requests\Tour\StoreBespokeRequest;
use App\Http\Requests\Tour\UpdateRequest;
use App\Interfaces\LocationRepositoryInterface;
use App\Interfaces\TourCategoryRepositoryInterface;
use App\Interfaces\TourRepositoryInterface;
use App\Models\Tour;
use App\Services\TourImageService;

class TourController extends Controller
{
    public function __construct(
        protected TourRepositoryInterface $tourRepository,
        protected LocationRepositoryInterface $cityRepository,
        protected TourCategoryRepositoryInterface $categoryRepository,
        protected TourImageService $imageService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'location_id', 'status', 'category_id']);
        $tours = $this->tourRepository->adminIndex($filters);
        $locations = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.index', compact('tours', 'locations', 'categories'));
    }

    public function create()
    {
        $locations = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.create', compact('locations', 'categories'));
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $tour = null;
        DB::transaction(function () use ($validated, $request, &$tour) {
            Log::info('=== ITINERARY DATA DEBUG ===');
            Log::info('Full validated data:', $validated);
            Log::info('Itinerary data:', ['data' => $validated['itinerary'] ?? 'NULL or MISSING']);
            Log::info('Itinerary type:', ['type' => gettype($validated['itinerary'] ?? null)]);
            if (isset($validated['itinerary'])) {
                Log::info('Itinerary count:', ['count' => count($validated['itinerary'])]);
                Log::info('Itinerary contents:', ['contents' => $validated['itinerary']]);
            }
            $itinerary = $validated['itinerary'] ?? [];
            $images = $validated['images'] ?? [];
            $coverIndex = $validated['cover_image_index'] ?? 0;
            unset($validated['itinerary'], $validated['images'], $validated['cover_image_index']);

            $tour = $this->tourRepository->store($validated);

            foreach ($itinerary as $index => $item) {
                // Save item if it has title OR description (at least some content)
                if (!empty($item['title']) || !empty($item['description'])) {
                    $tour->itineraryItems()->create([
                        'type' => $item['type'] ?? 'activity',
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null,
                        'duration_value' => $item['duration_value'] ?? null,
                        'duration_unit' => $item['duration_unit'] ?? 'minutes',
                        'sort_order' => $index,
                    ]);
                }
            }

            // Upload images
            if (!empty($images)) {
                $this->imageService->uploadImages($tour, $images, $coverIndex);
            }
        });

        return redirect()
            ->route('console.tours.show', $tour)
            ->with('success', 'Tour created successfully. You can now add itinerary and images.');
    }

    public function show(Tour $tour)
    {
        $tour = $this->tourRepository->find($tour->id);

        if (!$tour) {
            abort(404);
        }

        return view('admin.tours.show', compact('tour'));
    }

    public function edit(Tour $tour)
    {
        $tour = $this->tourRepository->find($tour->id);
        $locations = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.edit', compact('tour', 'locations', 'categories'));
    }

    public function update(UpdateRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $tour) {
            $itinerary = $validated['itinerary'] ?? [];
            $images = $validated['images'] ?? [];
            $coverImageId = $validated['cover_image_id'] ?? null;
            $deleteImages = $validated['delete_images'] ?? [];
            unset($validated['itinerary'], $validated['images'], $validated['cover_image_id'], $validated['delete_images']);

            $this->tourRepository->update($tour, $validated);

            $tour->itineraryItems()->delete();

            foreach ($itinerary as $index => $item) {
                if (!empty($item['title'])) {
                    $tour->itineraryItems()->create([
                        'type' => $item['type'] ?? 'activity',
                        'title' => $item['title'],
                        'description' => $item['description'] ?? null,
                        'sort_order' => $index,
                    ]);
                }
            }

            // Delete selected images
            if (!empty($deleteImages)) {
                $this->imageService->deleteImages($tour, $deleteImages);
            }

            // Upload new images
            if (!empty($images)) {
                $this->imageService->uploadImages($tour, $images);
            }

            // Set cover image
            if ($coverImageId) {
                $this->imageService->setCoverImage($tour, $coverImageId);
            }
        });

        return redirect()
            ->route('console.tours.show', $tour->fresh())
            ->with('success', 'Tour updated successfully.');
    }

    public function destroy(Tour $tour)
    {
        // Delete all images from storage
        $this->imageService->deleteAllImages($tour);

        $this->tourRepository->delete($tour);

        return redirect()
            ->route('console.tours.index')
            ->with('success', 'Tour deleted successfully.');
    }

    public function toggleStatus(Tour $tour)
    {
        $this->tourRepository->toggleStatus($tour);
        $status = $tour->status === 'published' ? 'published' : 'unpublished';

        return redirect()
            ->back()
            ->with('success', "Tour has been {$status}.");
    }

    public function createBespoke()
    {
        $locations = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.bespoke.create', compact('locations', 'categories'));
    }

    public function storeBespoke(StoreBespokeRequest $request)
    {
        $validated = $request->validated();

        $tour = null;
        DB::transaction(function () use ($validated, $request, &$tour) {
            $tourData = [
                'title' => $validated['title'],
                'slug' => null, // Will be generated by the repository
                'description' => $validated['description'],
                'tour_category_id' => 4, // Multiple Day Tours category for bespoke
                'location_id' => $validated['location_id'],
                'status' => 'draft',
                'tour_type' => 'bespoke',
                'code' => $validated['code'],
            ];

            $tour = $this->tourRepository->create($tourData);
        });

        return redirect()
            ->route('console.tours.show', $tour->fresh())
            ->with('success', 'Bespoke tour created successfully.');
    }
}
