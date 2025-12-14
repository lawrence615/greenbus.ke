<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function show(Location $location): View
    {
        $tours = $location->tours()
            ->where('status', 'published')
            ->with(['images', 'category'])
            ->paginate(12);

        return view('locations.show', [
            'location' => $location,
            'tours' => $tours,
        ]);
    }
}
