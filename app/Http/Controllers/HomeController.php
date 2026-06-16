<?php

namespace App\Http\Controllers;

use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->where('published_at', '<', now())
            // ->with(['primaryImage', 'maker', 'model', 'city', 'carType', 'fuelType'])
            // added to the model
            ->paginate(15);
        return view('home.index', ['cars' => $cars]);
    }
}
