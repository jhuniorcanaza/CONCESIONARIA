<?php

namespace App\Http\Controllers;

use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $carouselCars = Car::where('show_in_carousel', true)
            ->where('published_at', '<', now())
            ->where('is_approved', true)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $featuredCars = Car::where('is_featured', true)
            ->where('published_at', '<', now())
            ->where('is_approved', true)
            ->latest('published_at')
            ->limit(8)
            ->get();

        $cars = Car::latest()
            ->where('published_at', '<', now())
            ->where('is_approved', true)
            ->paginate(15);

        return view('home.index', [
            'carouselCars' => $carouselCars,
            'featuredCars' => $featuredCars,
            'cars' => $cars,
        ]);
    }
}
