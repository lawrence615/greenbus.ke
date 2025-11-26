<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\View\View;

class CityController extends Controller
{
    public function show(City $city): View
    {
        $tours = $city->tours()
            ->where('status', 'published')
            ->with(['images', 'category'])
            ->paginate(12);

        return view('cities.show', [
            'city' => $city,
            'tours' => $tours,
        ]);
    }
}
