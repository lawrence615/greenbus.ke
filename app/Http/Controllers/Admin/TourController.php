<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tour\StoreRequest;
use App\Http\Requests\Tour\UpdateRequest;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\TourCategoryRepositoryInterface;
use App\Interfaces\TourRepositoryInterface;
use App\Models\Tour;
use App\Services\TourImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    public function __construct(
        protected TourRepositoryInterface $tourRepository,
        protected CityRepositoryInterface $cityRepository,
        protected TourCategoryRepositoryInterface $categoryRepository,
        protected TourImageService $imageService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'city_id', 'status', 'category_id']);
        $tours = $this->tourRepository->adminIndex($filters);
        $cities = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.index', compact('tours', 'cities', 'categories'));
    }

    public function create()
    {
        $cities = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.create', compact('cities', 'categories'));
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $tour = null;
        DB::transaction(function () use ($validated, $request, &$tour) {
            $itinerary = $validated['itinerary'] ?? [];
            $images = $validated['images'] ?? [];
            $coverIndex = $validated['cover_image_index'] ?? 0;
            unset($validated['itinerary'], $validated['images'], $validated['cover_image_index']);

            $tour = $this->tourRepository->store($validated);

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

            // Upload images
            if (!empty($images)) {
                $this->imageService->uploadImages($tour, $images, $coverIndex);
            }
        });

        return redirect()
            ->route('console.tours.show', $tour)
            ->with('success', 'Tour created successfully.');
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
        $cities = $this->cityRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.edit', compact('tour', 'cities', 'categories'));
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
}
