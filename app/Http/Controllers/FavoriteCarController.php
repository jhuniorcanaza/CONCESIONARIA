<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class FavoriteCarController extends Controller
{
    public function toggle(Car $car)
    {
        $user = auth()->user();

        if ($user->favoriteCars()->where('car_id', $car->id)->exists()) {
            $user->favoriteCars()->detach($car);
            return response()->json(['status' => 'removed']);
        } else {
            $user->favoriteCars()->attach($car);
            return response()->json(['status' => 'added']);
        }
    }
}
