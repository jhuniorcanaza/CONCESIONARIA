<?php

namespace App\Http\Controllers;

use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::latest()
            ->where('published_at', '<', now())
            ->where('is_approved', true)
            ->paginate(15);
        return view('home.index', ['cars' => $cars]);
    }
}
