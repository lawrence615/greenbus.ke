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

            $tour = null;
            DB::transaction(function () use ($validated, &$tour) {
                $tour = $this->standardRepository->store($validated);
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

            DB::transaction(function () use ($validated, $tour) {
                $this->standardRepository->update($tour, $validated);
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
