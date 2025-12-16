<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourItineraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TourItineraryController extends Controller
{
    /**
     * Display the itinerary management page for a tour.
     */
    public function index(Tour $tour)
    {
        $itineraryItems = $tour->itineraryItems()->orderBy('sort_order')->get();
        
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
    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:start,transit,stopover,activity,end'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_value' => ['nullable', 'integer', 'min:0'],
            'duration_unit' => ['nullable', 'string', 'in:minutes,hours'],
        ]);

        try {
            DB::transaction(function () use ($tour, $validated) {
                $maxSortOrder = $tour->itineraryItems()->max('sort_order') ?? 0;
                
                $tour->itineraryItems()->create([
                    'type' => $validated['type'],
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'duration_value' => $validated['duration_value'] ?? null,
                    'duration_unit' => $validated['duration_unit'] ?? 'minutes',
                    'sort_order' => $maxSortOrder + 1,
                ]);
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
        // Verify the itinerary item belongs to the tour
        if ($itineraryItem->tour_id !== $tour->id) {
            abort(404);
        }

        return view('admin.tours.itinerary.edit', compact('tour', 'itineraryItem'));
    }

    /**
     * Update an itinerary item.
     */
    public function update(Request $request, Tour $tour, TourItineraryItem $itineraryItem)
    {
        // Verify the itinerary item belongs to the tour
        if ($itineraryItem->tour_id !== $tour->id) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:start,transit,stopover,activity,end'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_value' => ['nullable', 'integer', 'min:0'],
            'duration_unit' => ['nullable', 'string', 'in:minutes,hours'],
        ]);

        try {
            $itineraryItem->update([
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'duration_value' => $validated['duration_value'] ?? null,
                'duration_unit' => $validated['duration_unit'] ?? 'minutes',
            ]);

            return redirect()
                ->route('console.tours.itinerary.index', $tour)
                ->with('success', 'Itinerary item updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update itinerary item', [
                'tour_id' => $tour->id,
                'itinerary_item_id' => $itineraryItem->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

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
        // Verify the itinerary item belongs to the tour
        if ($itineraryItem->tour_id !== $tour->id) {
            abort(404);
        }

        try {
            $itineraryItem->delete();

            return redirect()
                ->route('console.tours.itinerary.index', $tour)
                ->with('success', 'Itinerary item deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete itinerary item', [
                'tour_id' => $tour->id,
                'itinerary_item_id' => $itineraryItem->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->with('error', 'Failed to delete itinerary item. Please try again.');
        }
    }

    /**
     * Reorder itinerary items.
     */
    public function reorder(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'item_ids' => ['required', 'array'],
            'item_ids.*' => ['required', 'integer', 'exists:tour_itinerary_items,id'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['item_ids'] as $sortOrder => $itemId) {
                    TourItineraryItem::where('id', $itemId)
                        ->update(['sort_order' => $sortOrder]);
                }
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to reorder itinerary items', [
                'tour_id' => $tour->id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return response()->json(['success' => false], 500);
        }
    }
}
