<?php

namespace App\Http\Controllers;

use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\TestimonialRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly CityRepositoryInterface $cityRepository,
        private readonly TestimonialRepositoryInterface $testimonialRepository,
    ) {}

    public function index(): View
    {
        $defaultCity = $this->cityRepository->getDefaultCity();
        $limit = 6;
        $featuredTours = $defaultCity
            ? $this->cityRepository->getFeaturedToursForCity($defaultCity, $limit)
            : collect();

        $totalFeaturedTours = $defaultCity
            ? $this->cityRepository->countFeaturedToursForCity($defaultCity)
            : 0;

        $testimonials = $this->testimonialRepository->getFeatured(6);

        return view('home', [
            'city' => $defaultCity,
            'featuredTours' => $featuredTours,
            'hasMoreTours' => $totalFeaturedTours > $limit,
            'testimonials' => $testimonials,
        ]);
    }
}
