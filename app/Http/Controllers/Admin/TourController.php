<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\TourRepositoryInterface;
use App\Models\City;
use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    public function __construct(
        protected TourRepositoryInterface $tourRepository
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'city_id', 'status', 'category_id']);
        $tours = $this->tourRepository->adminIndex($filters);
        $cities = City::orderBy('name')->get();
        $categories = TourCategory::orderBy('name')->get();

        return view('admin.tours.index', compact('tours', 'cities', 'categories'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        $categories = TourCategory::orderBy('name')->get();

        return view('admin.tours.create', compact('cities', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'tour_category_id' => 'nullable|exists:tour_categories,id',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'includes' => 'nullable|string',
            'important_information' => 'nullable|string',
            'duration_text' => 'nullable|string|max:100',
            'meeting_point' => 'nullable|string|max:255',
            'starts_at_time' => 'nullable|string|max:10',
            'is_daily' => 'boolean',
            'featured' => 'boolean',
            'base_price_adult' => 'required|numeric|min:0',
            'base_price_child' => 'nullable|numeric|min:0',
            'base_price_infant' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published',
            'itinerary' => 'nullable|array',
            'itinerary.*.title' => 'required_with:itinerary|string|max:255',
            'itinerary.*.description' => 'nullable|string',
        ]);

        $validated['is_daily'] = $request->boolean('is_daily');
        $validated['featured'] = $request->boolean('featured');

        $tour = null;
        DB::transaction(function () use ($validated, &$tour) {
            $itinerary = $validated['itinerary'] ?? [];
            unset($validated['itinerary']);
            
            $tour = $this->tourRepository->store($validated);
            
            // Save itinerary items
            foreach ($itinerary as $index => $item) {
                if (!empty($item['title'])) {
                    $tour->itineraryItems()->create([
                        'title' => $item['title'],
                        'description' => $item['description'] ?? null,
                        'order' => $index + 1,
                    ]);
                }
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
        $cities = City::orderBy('name')->get();
        $categories = TourCategory::orderBy('name')->get();

        return view('admin.tours.edit', compact('tour', 'cities', 'categories'));
    }

    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'tour_category_id' => 'nullable|exists:tour_categories,id',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'includes' => 'nullable|string',
            'important_information' => 'nullable|string',
            'duration_text' => 'nullable|string|max:100',
            'meeting_point' => 'nullable|string|max:255',
            'starts_at_time' => 'nullable|string|max:10',
            'is_daily' => 'boolean',
            'featured' => 'boolean',
            'base_price_adult' => 'required|numeric|min:0',
            'base_price_child' => 'nullable|numeric|min:0',
            'base_price_infant' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published',
            'itinerary' => 'nullable|array',
            'itinerary.*.title' => 'required_with:itinerary|string|max:255',
            'itinerary.*.description' => 'nullable|string',
        ]);

        $validated['is_daily'] = $request->boolean('is_daily');
        $validated['featured'] = $request->boolean('featured');

        DB::transaction(function () use ($validated, $tour) {
            $itinerary = $validated['itinerary'] ?? [];
            unset($validated['itinerary']);
            
            $this->tourRepository->update($tour, $validated);
            
            // Delete existing itinerary items and recreate
            $tour->itineraryItems()->delete();
            
            foreach ($itinerary as $index => $item) {
                if (!empty($item['title'])) {
                    $tour->itineraryItems()->create([
                        'title' => $item['title'],
                        'description' => $item['description'] ?? null,
                        'order' => $index + 1,
                    ]);
                }
            }
        });

        return redirect()
            ->route('console.tours.show', $tour->fresh())
            ->with('success', 'Tour updated successfully.');
    }

    public function destroy(Tour $tour)
    {
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
