<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\MainRepositoryInterface;
use App\Models\Location;
use App\Models\Tour;
use App\Interfaces\TourRepositoryInterface;

class TourController extends Controller
{

    public function __construct(
        private readonly MainRepositoryInterface $tourRepository,
    ) {
    }

    public function index(Location $location): View
    {
        $search = request('q');

        $tours = $this->tourRepository->guestIndex($location, 12, $search);

        return view('tours.index', [
            'location' => $location,
            'tours' => $tours,
        ]);
    }

    public function show(Location $location, Tour $tour): View
    {
        $tour = $this->tourRepository->get($location, $tour);

        return view('tours.show', [
            'location' => $location,
            'tour' => $tour,
        ]);
    }
}
