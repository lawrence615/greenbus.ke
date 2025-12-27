<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\PricingRepositoryInterface;
use App\Models\Tour;
use App\Http\Requests\Tour\Pricing\StoreRequest;
use App\Models\TourPricing;

class PricingController extends Controller
{

    public function __construct(private readonly PricingRepositoryInterface $pricingRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Tour $tour)
    {
        return view('admin.tours.pricing.create', compact('tour'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Tour $tour)
    {
        try {
            $validated = $request->validated();
            Log::info('store: controller', ['data' => $validated]);
            $this->pricingRepository->store($tour, $validated);
            return redirect()->route('admin.tours.index')->with('success', 'Tour created successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Unable to save the pricing: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TourPricing $tourPricing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TourPricing $tourPricing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TourPricing $tourPricing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour, TourPricing $tourPricing)
    {
        try {
            $this->pricingRepository->delete($tourPricing);
            return response()->json(['success' => true, 'message' => 'Pricing deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete pricing: ' . $e->getMessage()], 500);
        }
    }
}
