<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $carouselCars = Car::where('show_in_carousel', true)
            ->where('published_at', '<', now())
            ->where('is_approved', true)
            ->where(function ($query) {
                $query->whereNull('is_featured_until')
                    ->orWhereDate('is_featured_until', '>=', now());
            })
            ->latest('published_at')
            ->limit(5)
            ->get();

        $featuredCars = Car::where('is_featured', true)
            ->where('published_at', '<', now())
            ->where('is_approved', true)
            ->where(function ($query) {
                $query->whereNull('is_featured_until')
                    ->orWhereDate('is_featured_until', '>=', now());
            })
            ->latest('published_at')
            ->limit(8)
            ->get();

        $cars = Car::orderBy('created_at', 'desc')
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
