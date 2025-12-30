<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\StandardRepositoryInterface;
use App\Interfaces\Tour\CategoryRepositoryInterface;
use App\Interfaces\LocationRepositoryInterface;
use App\Http\Requests\Tour\Standard\StoreRequest;
use App\Http\Requests\Tour\Standard\UpdateRequest;
use App\Models\Tour;

class StandardController extends Controller
{
    public function __construct(
        private readonly StandardRepositoryInterface $standardRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly LocationRepositoryInterface $locationRepository,
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'location_id', 'status', 'category_id']);
        $tours = $this->standardRepository->index($filters);
        $locations = $this->locationRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin.tours.standard.index', compact('tours', 'locations', 'categories'));
    }

    public function create()
    {
        try {
            $locations = $this->locationRepository->getAll();
            $categories = $this->categoryRepository->getAll();

            return view('admin.tours.standard.create', compact('locations', 'categories'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Unable to load the create tour page: ' . $e->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validated = $request->validated();

            // Extract pricing data from validated data
            $pricingData = [
                'adult' => $validated['base_price_adult'] ?? null,
                'senior' => $validated['base_price_senior'] ?? null,
                'youth' => $validated['base_price_youth'] ?? null,
                'child' => $validated['base_price_child'] ?? null,
                'infant' => $validated['base_price_infant'] ?? null,
            ];

            // Remove pricing fields from tour data
            $tourData = collect($validated)->except([
                'base_price_adult',
                'base_price_senior', 
                'base_price_youth',
                'base_price_child',
                'base_price_infant'
            ])->toArray();

            $tour = null;
            DB::transaction(function () use ($tourData, $pricingData, &$tour) {
                $tour = $this->standardRepository->store($tourData);
                
                // Create tour pricings
                foreach ($pricingData as $personType => $price) {
                    if ($price !== null && $price > 0) {
                        $tour->pricings()->create([
                            'person_type' => $personType,
                            'price' => (int) $price,
                            'discount' => 0,
                            'discounted_price' => (int) $price,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('console.tours.standard.show', $tour)
                ->with('success', 'Tour created successfully. You can now add itinerary and images.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Unable to save the tour: ' . $e->getMessage());
        }
    }

    public function show(Tour $tour)
    {
        $tour = $this->standardRepository->find($tour->id);

        if (!$tour) {
            abort(404);
        }

        return view('admin.tours.standard.show', compact('tour'));
    }

    public function edit(Tour $tour)
    {
        try {
            $tour = $this->standardRepository->find($tour->id);
            $locations = $this->locationRepository->getAll();
            $categories = $this->categoryRepository->getAll();

            return view('admin.tours.standard.edit', compact('tour', 'locations', 'categories'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Unable to load the edit tour page: ' . $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, Tour $tour)
    {
        try {
            $validated = $request->validated();

            // Extract pricing data from validated data
            $pricingData = [
                'adult' => $validated['base_price_adult'] ?? null,
                'senior' => $validated['base_price_senior'] ?? null,
                'youth' => $validated['base_price_youth'] ?? null,
                'child' => $validated['base_price_child'] ?? null,
                'infant' => $validated['base_price_infant'] ?? null,
            ];

            // Remove pricing fields from tour data
            $tourData = collect($validated)->except([
                'base_price_adult',
                'base_price_senior', 
                'base_price_youth',
                'base_price_child',
                'base_price_infant'
            ])->toArray();

            DB::transaction(function () use ($tourData, $pricingData, $tour) {
                $this->standardRepository->update($tour, $tourData);
                
                // Delete existing pricings
                $tour->pricings()->delete();
                
                // Create new tour pricings
                foreach ($pricingData as $personType => $price) {
                    if ($price !== null && $price > 0) {
                        $tour->pricings()->create([
                            'person_type' => $personType,
                            'price' => (int) $price,
                            'discount' => 0,
                            'discounted_price' => (int) $price,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('console.tours.standard.show', $tour->fresh())
                ->with('success', 'Tour updated successfully.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Unable to update tour details: ' . $e->getMessage());
        }
    }
}
