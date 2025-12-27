<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Models\Location;

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
