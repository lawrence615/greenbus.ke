<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Tour;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $defaultCity = City::where('slug', 'nairobi')->where('is_active', true)->first();

        $featuredTours = Tour::with('city')
            ->whereHas('city', function ($query) {
                $query->where('slug', 'nairobi')->where('is_active', true);
            })
            ->where('featured', true)
            ->where('status', 'published')
            ->take(6)
            ->get();

        return view('home', [
            'city' => $defaultCity,
            'featuredTours' => $featuredTours,
        ]);
    }
}
