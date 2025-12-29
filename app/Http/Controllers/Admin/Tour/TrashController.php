<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Repositories\Tour\MainRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TrashController extends Controller
{
    public function __construct(
        private MainRepository $mainRepository
    ) {}

    /**
     * Display a listing of deleted tours.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'location_id', 'category_id']);
        $tours = $this->mainRepository->trashed($filters);

        return view('admin.tours.trash.index', compact('tours'));
    }

    /**
     * Restore the specified tour.
     */
    public function restore(Tour $tour): RedirectResponse
    {
        $this->mainRepository->restore($tour);

        return redirect()
            ->route('console.tours.trash.index')
            ->with('success', 'Tour restored successfully.');
    }

    /**
     * Permanently delete the specified tour.
     */
    public function destroy(Tour $tour): RedirectResponse
    {
        $this->mainRepository->forceDelete($tour);

        return redirect()
            ->route('console.tours.trash.index')
            ->with('success', 'Tour permanently deleted.');
    }
}
