<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarImage>
 */
class CarImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'image_path' => $this->faker->imageUrl(),
            'image_path' => 'https://picsum.photos/seed/' . rand(1, 1000) . '/640/480',
            'position' => function ($attributes) {
                return Car::find($attributes['car_id'])->images()->count() + 1;
            },
        ];
    }
}
