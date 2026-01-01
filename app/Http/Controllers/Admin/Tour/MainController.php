<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\MainRepositoryInterface;
use App\Interfaces\Tour\CategoryRepositoryInterface;
use App\Interfaces\LocationRepositoryInterface;
use App\Models\Tour;

class MainController extends Controller
{
    public function __construct(
        private readonly MainRepositoryInterface $mainRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly LocationRepositoryInterface $locationRepository,
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'location_id', 'category_id', 'status', 'is_the_bus_tour']);

        $locations = $this->locationRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        $tours = $this->mainRepository->adminIndex($filters);

        return view('admin.tours.index', compact('tours', 'locations', 'categories'));
    }

    public function show()
    {
        throw new \Exception('Not implemented');
    }

    public function destroy(Tour $tour)
    {
        $this->mainRepository->delete($tour);

        return redirect()
            ->route('console.tours.index')
            ->with('success', 'Tour deleted successfully.');
    }

    public function toggleStatus(Tour $tour)
    {
        $this->mainRepository->toggleStatus($tour);
        $status = $tour->status === 'published' ? 'published' : 'unpublished';

        return redirect()
            ->back()
            ->with('success', "Tour has been {$status}.");
    }
}
