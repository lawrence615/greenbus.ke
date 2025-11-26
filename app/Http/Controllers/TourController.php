<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Tour;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(City $city): View
    {
        $tours = $city->tours()
            ->where('status', 'published')
            ->with(['images', 'category'])
            ->paginate(12);

        return view('tours.index', [
            'city' => $city,
            'tours' => $tours,
        ]);
    }

    public function show(City $city, Tour $tour): View
    {
        if ($tour->city_id !== $city->id) {
            abort(404);
        }

        $tour->load(['images', 'category']);

        return view('tours.show', [
            'city' => $city,
            'tour' => $tour,
        ]);
    }
}
