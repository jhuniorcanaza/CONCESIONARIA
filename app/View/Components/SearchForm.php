<?php

namespace App\View\Components;

use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Services\CarFormService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SearchForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $action ="search",
        public string $method = "GET" 
    )
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $filtered = request()->only(['maker_id', 'model_id', 'state_id', 'city_id', 'car_type_id', 'fuel_type_id', 'price_from', 'price_to', 'year_from', 'year_to', 'mileage']);    
        
        $carFormService = new CarFormService();

        $data = $carFormService->getCarFormData();

        // if (Cache::has('carFormData')) {
        //     $data = Cache::get('carFormData');
        // } else {
        //     $makers = Maker::oldest("name")->get();
        //     $models = Model::oldest("name")->get();
        //     $states = State::oldest("name")->get();
        //     $cities = City::oldest("name")->get();
        //     $types  = CarType::oldest("name")->get();
        //     $fuelTypes = FuelType::oldest("name")->get();
    
        //     $milage = [
        //         ['id' => '10000', 'name' => '10,000 or less'],
        //         ['id' => '20000', 'name' => '20,000 or less'],
        //         ['id' => '30000', 'name' => '30,000 or less'],
        //         ['id' => '40000', 'name' => '40,000 or less'],
        //         ['id' => '50000', 'name' => '50,000 or less'],
        //         ['id' => '60000', 'name' => '60,000 or less'],
        //         ['id' => '70000', 'name' => '70,000 or less'],
        //         ['id' => '80000', 'name' => '80,000 or less'],
        //         ['id' => '90000', 'name' => '90,000 or less'],
        //         ['id' => '100000', 'name' => '100,000 or less'],
        //         ['id' => '150000', 'name' => '150,000 or less'],
        //         ['id' => '200000', 'name' => '200,000 or less'],
        //         ['id' => '250000', 'name' => '250,000 or less'],
        //         ['id' => '300000', 'name' => '300,000 or less'],
        //     ];
            
        //     $data = [
        //         'models' => $models,
        //         'makers' => $makers,
        //         'states' => $states,
        //         'cities' => $cities,
        //         'types' => $types,
        //         'fuelTypes' => $fuelTypes,
        //         'milage' => $milage
        //     ];
            
        //     Cache::put('carFormData', $data, 3600);
        // }

        $data['filtered'] = $filtered;

        return view('components.search-form', $data);
    }
}
