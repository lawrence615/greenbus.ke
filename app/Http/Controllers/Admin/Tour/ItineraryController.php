<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\ItineraryRepositoryInterface;
use App\Models\Tour;
use App\Http\Requests\Tour\Itinerary\StoreRequest;
use App\Http\Requests\Tour\Itinerary\UpdateRequest;
use App\Models\TourItineraryItem;
use App\Http\Requests\Tour\Itinerary\ReorderRequest;

class ItineraryController extends Controller
{
    public function __construct(private readonly ItineraryRepositoryInterface $itineraryRepository) {}

    /**
     * Display the itinerary management page for a tour.
     */
    public function index(Tour $tour)
    {
        $itineraryItems = $this->itineraryRepository->list($tour);
        return view('admin.tours.itinerary.index', compact('tour', 'itineraryItems'));
    }

    /**
     * Show the form for creating a new itinerary item.
     */
    public function create(Tour $tour)
    {
        return view('admin.tours.itinerary.create', compact('tour'));
    }

    /**
     * Store a new itinerary item.
     */
    public function store(StoreRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($tour, $validated) {
                $this->itineraryRepository->create($tour, $validated);
            });

            return redirect()
                ->route('console.tours.itinerary.index', $tour)
                ->with('success', 'Itinerary item added successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create itinerary item', [
                'tour_id' => $tour->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to add itinerary item. Please try again.');
        }
    }

    /**
     * Show the form for editing an itinerary item.
     */
    public function edit(Tour $tour, TourItineraryItem $itineraryItem)
    {
        $this->itineraryRepository->assertBelongsToTour($tour, $itineraryItem);
        return view('admin.tours.itinerary.edit', compact('tour', 'itineraryItem'));
    }

    /**
     * Update an itinerary item.
     */
    public function update(UpdateRequest $request, Tour $tour, TourItineraryItem $itineraryItem)
    {
        $this->itineraryRepository->assertBelongsToTour($tour, $itineraryItem);

        $validated = $request->validated();

        try {
            $this->itineraryRepository->update($itineraryItem, $validated);
            return redirect()
                ->route('console.tours.itinerary.index', $tour)
                ->with('success', 'Itinerary item updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update itinerary item. Please try again.');
        }
    }

    /**
     * Delete an itinerary item.
     */
    public function destroy(Tour $tour, TourItineraryItem $itineraryItem)
    {
        $this->itineraryRepository->assertBelongsToTour($tour, $itineraryItem);

        try {
            $this->itineraryRepository->delete($itineraryItem);
            return redirect()
                ->route('console.tours.itinerary.index', $tour)
                ->with('success', 'Itinerary item deleted successfully!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete itinerary item. Please try again.');
        }
    }

    /**
     * Reorder itinerary items.
     */
    public function reorder(ReorderRequest $request, Tour $tour)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($tour, $validated) {
                $this->itineraryRepository->reorder($tour, $validated['item_ids']);
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
