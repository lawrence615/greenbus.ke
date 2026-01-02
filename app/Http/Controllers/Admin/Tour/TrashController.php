<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Repositories\Tour\MainRepository;
use App\Interfaces\LocationRepositoryInterface;
use App\Interfaces\Tour\CategoryRepositoryInterface;
use App\Models\Tour;

class TrashController extends Controller
{
    public function __construct(
        private MainRepository $mainRepository,
        private LocationRepositoryInterface $locationRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Display a listing of deleted tours.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'location_id', 'category_id']);
        $locations = $this->locationRepository->getAll();
        $categories = $this->categoryRepository->getAll();
        $tours = $this->mainRepository->trashed($filters);
        Log::info('Return all deleted tours: ', compact('tours'));

        return view('admin.tours.trashed.index', compact('tours', 'locations', 'categories'));
    }

    /**
     * Restore the specified tour.
     */
    public function restore(int $tour): RedirectResponse
    {
        $tourModel = Tour::withTrashed()->findOrFail($tour);
        Log::info('Restoring tour: ' . $tourModel->slug);
        $this->mainRepository->restore($tourModel);

        return redirect()
            ->route('console.tours.trash.index')
            ->with('success', 'Tour restored successfully.');
    }

    /**
     * Permanently delete the specified tour.
     */
    public function destroy(int $tour): RedirectResponse
    {
        $tourModel = Tour::withTrashed()->findOrFail($tour);
        Log::info('Permanently deleting tour: ' . $tourModel->slug);
        $this->mainRepository->forceDelete($tourModel);

        return redirect()
            ->route('console.tours.trash.index')
            ->with('success', 'Tour permanently deleted.');
    }
}
