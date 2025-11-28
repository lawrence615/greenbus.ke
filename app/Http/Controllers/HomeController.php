<?php

namespace App\Http\Controllers;

use App\Interfaces\CityRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly CityRepositoryInterface $cityRepository,
    ) {}

    public function index(): View
    {
        $defaultCity = $this->cityRepository->getDefaultCity();
        $featuredTours = $defaultCity
            ? $this->cityRepository->getFeaturedToursForCity($defaultCity, 6)
            : collect();

        return view('home', [
            'city' => $defaultCity,
            'featuredTours' => $featuredTours,
        ]);
    }
}
