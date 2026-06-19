<?php

namespace Database\Factories;

use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        $carType = CarType::inRandomOrder()->first();

        $data = [
            'maker_id' => Model::inRandomOrder()->first()->maker_id,
            'model_id' => function ($attributes) {
                return Model::where('maker_id', $attributes['maker_id'])->inRandomOrder()->first()->id;
            },
            'year' => $this->faker->year(),
            'price' => ((int)$this->faker->randomFloat(2, 5, 100)) * 1000,
            'vin' => $this->faker->unique()->regexify('[A-Z0-9]{17}'),
            'mileage' => $this->faker->numberBetween(5, 500000),
            'car_type_id' => $carType->id,
            'fuel_type_id' => FuelType::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'city_id' => City::inRandomOrder()->first()->id,
            'address' => $this->faker->address(),
            'phone' => function($attributes) {
                return User::find($attributes['user_id'])->phone;
            },
            'description' => $this->faker->text(1500),
            'published_at' => $this->faker->optional(0.9)->dateTimeBetween('-10 month', '-1 day'),
            'is_approved' => $this->faker->boolean(80),
        ];

        if ($carType->name === 'Auto') {
            $data['body_type'] = $this->faker->randomElement(['Sedán', 'Hatchback', 'Camioneta Pick-up', 'Vagoneta SUV', 'Minibús', 'Coupé']);
            $data['drive_type'] = $this->faker->randomElement(['4x4', '4x2', 'Delantera', 'Trasera']);
        } elseif ($carType->name === 'Motocicleta') {
            $data['engine_cc'] = $this->faker->randomElement([150, 200, 250, 400, 600, 750, 1000]);
            $data['bike_type'] = $this->faker->randomElement(['Scooter', 'Deportiva', 'Enduro/Cross', 'Custom/Chopper', 'Cuadratrack']);
            $data['start_type'] = $this->faker->randomElement(['Eléctrico', 'Pedal', 'Ambos']);
        } elseif ($carType->name === 'Maquinaria Pesada') {
            $data['road_type'] = $this->faker->randomElement(['Llantas', 'Orugas']);
            $data['machinery_type'] = $this->faker->randomElement(['Excavadora', 'Retroexcavadora', 'Tractor Agrícola', 'Cosechadora', 'Montacargas']);
        }

        return $data;
    }
}
