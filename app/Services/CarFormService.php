<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\City;
use App\Models\CarType;
use App\Models\FuelType;

class CarFormService
{
    public function getCarFormData()
    {
        return Cache::remember('carFormData', 3600, function () {
            return [
                'makers' => Maker::oldest("name")->get(),
                'models' => Model::oldest("name")->get(),
                'states' => State::oldest("name")->get(),
                'cities' => City::oldest("name")->get(),
                'types' => CarType::oldest("name")->get(),
                'fuelTypes' => FuelType::oldest("name")->get(),
                'bodyTypes' => [
                    (object)['id' => 'Sedán', 'name' => 'Sedán'],
                    (object)['id' => 'Hatchback', 'name' => 'Hatchback'],
                    (object)['id' => 'Camioneta Pick-up', 'name' => 'Camioneta Pick-up'],
                    (object)['id' => 'Vagoneta SUV', 'name' => 'Vagoneta SUV'],
                    (object)['id' => 'Minibús', 'name' => 'Minibús'],
                    (object)['id' => 'Coupé', 'name' => 'Coupé'],
                    (object)['id' => 'Descapotable', 'name' => 'Descapotable'],
                ],
                'driveTypes' => [
                    (object)['id' => '4x4', 'name' => '4x4'],
                    (object)['id' => '4x2', 'name' => '4x2'],
                    (object)['id' => 'Delantera', 'name' => 'Delantera'],
                    (object)['id' => 'Trasera', 'name' => 'Trasera'],
                ],
                'bikeTypes' => [
                    (object)['id' => 'Scooter', 'name' => 'Scooter'],
                    (object)['id' => 'Deportiva', 'name' => 'Deportiva'],
                    (object)['id' => 'Enduro/Cross', 'name' => 'Enduro/Cross'],
                    (object)['id' => 'Custom/Chopper', 'name' => 'Custom/Chopper'],
                    (object)['id' => 'Cuadratrack', 'name' => 'Cuadratrack'],
                ],
                'startTypes' => [
                    (object)['id' => 'Eléctrico', 'name' => 'Eléctrico'],
                    (object)['id' => 'Pedal', 'name' => 'Pedal'],
                    (object)['id' => 'Ambos', 'name' => 'Ambos'],
                ],
                'roadTypes' => [
                    (object)['id' => 'Llantas', 'name' => 'Llantas'],
                    (object)['id' => 'Orugas', 'name' => 'Orugas'],
                ],
                'machineryTypes' => [
                    (object)['id' => 'Excavadora', 'name' => 'Excavadora'],
                    (object)['id' => 'Retroexcavadora', 'name' => 'Retroexcavadora'],
                    (object)['id' => 'Tractor Agrícola', 'name' => 'Tractor Agrícola'],
                    (object)['id' => 'Cosechadora', 'name' => 'Cosechadora'],
                    (object)['id' => 'Montacargas', 'name' => 'Montacargas'],
                    (object)['id' => 'Cargador Frontal', 'name' => 'Cargador Frontal'],
                    (object)['id' => 'Motoniveladora', 'name' => 'Motoniveladora'],
                    (object)['id' => 'Compactadora', 'name' => 'Compactadora'],
                ],
                'milage' => [
                    ['id' => '10000', 'name' => '10,000 or less'],
                    ['id' => '20000', 'name' => '20,000 or less'],
                    ['id' => '30000', 'name' => '30,000 or less'],
                    ['id' => '40000', 'name' => '40,000 or less'],
                    ['id' => '50000', 'name' => '50,000 or less'],
                    ['id' => '60000', 'name' => '60,000 or less'],
                    ['id' => '70000', 'name' => '70,000 or less'],
                    ['id' => '80000', 'name' => '80,000 or less'],
                    ['id' => '90000', 'name' => '90,000 or less'],
                    ['id' => '100000', 'name' => '100,000 or less'],
                    ['id' => '150000', 'name' => '150,000 or less'],
                    ['id' => '200000', 'name' => '200,000 or less'],
                    ['id' => '250000', 'name' => '250,000 or less'],
                    ['id' => '300000', 'name' => '300,000 or less'],
                ]
            ];
        });
    }
}
