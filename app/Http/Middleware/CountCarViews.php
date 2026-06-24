<?php

namespace App\Http\Middleware;

use App\Models\Car;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountCarViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $car = $request->route('car');

        if ($car instanceof Car) {
            $viewedCars = session()->get('viewed_cars', []);

            if (! in_array($car->id, $viewedCars, true)) {
                $viewedCars[] = $car->id;
                session(['viewed_cars' => $viewedCars]);
                $car->increment('views_count');
            }
        }

        return $next($request);
    }
}
