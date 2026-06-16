<?php

namespace App\Http\Controllers;

use App\DTO\ActionResult;
use App\Actions\DestroyCarAction;
use App\Actions\UpdateCarAction;
use App\Actions\StoreCarAction;
use App\Services\CarFormService;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{    

    public function __construct(protected CarFormService $carFormService) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = auth()->user()->cars()->latest()->paginate(6);

        return view('car.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $data = $this->carFormService->getCarFormData();

        return view('car.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarRequest $request, StoreCarAction $action)
    {
        /** @var ActionResult $result */
        $result = $action->handle($request->validated());
        
        if ($result->success) {
            return redirect()->route('car.index')->with('success', $result->message);
        }
    
        return back()->with('error', $result->message)->withInput();

    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return view('car.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $data = $this->carFormService->getCarFormData();

        $data['car'] = $car;

        return view('car.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, Car $car, UpdateCarAction $action)
    {

        /** @var ActionResult $result */
        $result = $action->handle($request->validated(), $car);
        
        if ($result->success) {
            return redirect()->route('car.show', $car)->with('success', $result->message);
        }
    
        return back()->with('error', $result->message)->withInput();        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car, DestroyCarAction $action)
    {
        /** @var ActionResult $result */
        $result = $action->handle($car);
        
        if ($result->success) {
            return redirect()->route('car.index')->with('success', $result->message);
        }
    
        return redirect()->route('car.index')->with('error', $result->message);        
    }

    public function search(Request $request)
    {
        $query = Car::select('cars.*') // needs for joins
            ->wherePast('published_at') //::where('published_at', '<', now()
            ->orderBy('published_at', 'desc') // ->latest('published_at')            
            ->filter(request()->only(['maker_id', 'model_id', 'state_id', 'city_id', 'car_type_id', 'fuel_type_id', 'price_from', 'price_to', 'year_from', 'year_to', 'mileage', 'sort']));
        
        $cars = $query->paginate(12)->withQueryString();

        // $carCount = $query->count(); //not needed due to pagination $cars->total()

        return view('car.search', compact('cars'));
    }

    public function watchlist() {

        $cars = auth()->user()
            ->favoriteCars()
            ->paginate(12);

        return view('car.watchlist', compact('cars'));
    }

    public function getPhone(Car $car) {
        return response()->json(['phone' => $car->phone]);
    }
}
