<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use App\Models\City;
use App\Models\Tour;
use App\Interfaces\TourRepositoryInterface;

class TourController extends Controller
{

    public function __construct(
        private readonly TourRepositoryInterface $tourRepository,
    ) {
    }

    public function index(City $city): View
    {
        $search = request('q');

        $tours = $this->tourRepository->index($city, 12, $search);

        return view('tours.index', [
            'city' => $city,
            'tours' => $tours,
        ]);
    }

    public function show(City $city, Tour $tour): View
    {
        $tour = $this->tourRepository->get($city, $tour);

        return view('tours.show', [
            'city' => $city,
            'tour' => $tour,
        ]);
    }
}
