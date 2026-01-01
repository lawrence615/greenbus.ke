<?php

namespace App\Http\Controllers;

use App\Interfaces\LocationRepositoryInterface;
use App\Interfaces\TestimonialRepositoryInterface;
use App\Interfaces\Tour\MainRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly LocationRepositoryInterface $cityRepository,
        private readonly TestimonialRepositoryInterface $testimonialRepository,
        private readonly MainRepositoryInterface $tourRepository,
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
        
        // Get the bus tour
        $busTour = $this->tourRepository->getBusTour();

        return view('home', [
            'location' => $defaultCity,
            'featuredTours' => $featuredTours,
            'hasMoreTours' => $totalFeaturedTours > $limit,
            'testimonials' => $testimonials,
            'busTour' => $busTour,
        ]);
    }
}
