<?php

namespace Tests\Feature;

use App\Models\CarFeature;
use App\Models\State;
use App\Models\User;
use App\Models\Car;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    private function prepareCarData() {

        User::factory()->create();

        $maker = Maker::factory()->create();
        Model::factory()->create(['maker_id' => $maker->id]);
        CarType::factory()->create();
        FuelType::factory()->create();
        $state = State::factory()->create();
        City::factory()->create(['state_id' => $state->id]);
    }

    public function test_guest_can_access_a_show_car_page()
    {
        $this->prepareCarData();
        $car = Car::factory()->create();
        CarFeature::factory()->create(['car_id' => $car->id]);
        $car->images()->create([
            'image_path' => 'cars/image1.jpg',
            'position' => 0,
        ]);

        $response = $this->get(route('car.show', $car));

        $response->assertStatus(200);
    }

    public function test_guest_can_get_full_car_phone_number()
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
        ]);
        $this->prepareCarData();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'phone' => $user->phone,
        ]);

        $response = $this->post(route('car.get-phone', $car));
        $response->assertStatus(200);
        $response->assertJson([
            'phone' => $user->phone,
        ]);
    }

    public function test_guest_can_access_search_car_page()
    {
        $user = User::factory()->create();
        $this->prepareCarData();
        $car = Car::factory()->create([
            'user_id' => $user->id, 
            'published_at' => now()->subDay()->toDateTimeString() // date in the past
        ]);
        CarFeature::factory()->create(['car_id' => $car->id]);

        $searchData = $car->only([
            'maker_id', 
            'model_id', 
            'city_id', 
            'car_type_id', 
            'fuel_type_id', 
            'mileage'
        ]);
        $searchData['price_from'] = $car->price - 1;
        $searchData['price_to'] = $car->price + 1;
        $searchData['year_from'] = $car->year - 1;
        $searchData['year_to'] = $car->year + 1;

        $response = $this->get(route('car.search', $searchData));

        $response->assertStatus(200);

        $response->assertViewIs('car.search');

        $response->assertViewHas('cars', function ($cars) use ($car) {
            // dd($cars->toArray());
            return $cars->count() === 1 && $cars->first()->is($car);
        });
        $response->assertSee('Define your search criteria');

    }

    public function test_user_can_access_car_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/car');

        $response->assertStatus(200);
    }

    public function test_user_can_access_car_create_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/car/create');

        $response->assertStatus(200);
    }

    public function test_user_can_store_car()
    {
        // Prepare the test environment
        Storage::fake('public'); // Подменяем файловую систему для тестов

        $user = User::factory()->create();

        $maker = Maker::factory()->create();
        $model = Model::factory()->create(['maker_id' => $maker->id]);
        $carType = CarType::factory()->create();
        $fuelType = FuelType::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create([
            'state_id' => $state->id,
        ]);

        $images = [
            'temp/image1.jpg',
            'temp/image2.jpg',
        ];

        foreach ($images as $image) {
            Storage::disk('public')->put($image, 'dummy content');
        }

        $data = [
            'maker_id' => $maker->id,
            'model_id' => $model->id,
            'year' => 2020,
            'price' => 15000,
            'vin' => '1HGCM82633A123456',
            'mileage' => 50000,
            'car_type_id' => $carType->id,
            'fuel_type_id' => $fuelType->id,
            'city_id' => $city->id,
            'address' => '123 Main St',
            'phone' => '1234567890',
            'description' => 'A great car!',
            'published_at' => true,

            // Features
            'abs' => true,
            'air_conditioning' => true,
            'power_windows' => true,
            'power_door_locks' => true,
            'cruise_control' => true,
            'bluetooth_connectivity' => true,
            'remote_start' => false,
            'gps_navigation' => true,
            'heated_seats' => false,
            'climate_control' => true,
            'rear_parking_sensors' => false,
            'leather_seats' => true,

            // Images
            'images' => $images,
        ];

        $response = $this->actingAs($user)->post(route('car.store'), $data);

        $response->assertRedirect(route('car.index'));

        $this->assertDatabaseHas('cars', [
            'maker_id' => $maker->id,
            'model_id' => $model->id,
            'year' => 2020,
            'price' => 15000,
            'vin' => '1HGCM82633A123456',
            'mileage' => 50000,
            'car_type_id' => $carType->id,
            'fuel_type_id' => $fuelType->id,
            'city_id' => $city->id,
            'address' => '123 Main St',
            'phone' => '1234567890',
        ]);

        foreach ($images as $key => $image) {
            $newPath = 'cars/' . basename($image);
            Storage::disk('public')->assertExists($newPath);

            $this->assertDatabaseHas('car_images', [
                'car_id' => Car::first()->id,
                'image_path' => $newPath,
                'position' => $key,
            ]);
        }
    }
    public function test_user_can_access_car_edit_page()
    {

        $this->prepareCarData();

        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        CarFeature::factory()->create(['car_id' => $car->id]);
        $car->images()->create([
            'image_path' => 'cars/image1.jpg',
            'position' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('car.edit', $car));

        $response->assertStatus(200);
    }

    public function test_user_can_update_car()
    {
        Storage::fake('public');

        $this->prepareCarData();

        $user = User::factory()->create();

        $car = Car::factory()->create(['user_id' => $user->id]);
        CarFeature::factory()->create(['car_id' => $car->id]);
        $car->images()->create([
            'image_path' => 'cars/image1.jpg',
            'position' => 0,
        ]);


        $maker = Maker::factory()->create();
        $model = Model::factory()->create(['maker_id' => $maker->id]);
        $carType = CarType::factory()->create();
        $fuelType = FuelType::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create([
            'state_id' => $state->id,
        ]);

        $images = [
            'temp/image2.jpg',
            'temp/image3.jpg',
        ];

        foreach ($images as $image) {
            Storage::disk('public')->put($image, 'dummy content');
        }

        $carData = [
            'maker_id' => $maker->id,
            'model_id' => $model->id,
            'year' => 2020,
            'price' => 15000,
            'vin' => '1HGCM82633A123456',
            'mileage' => 50000,
            'car_type_id' => $carType->id,
            'fuel_type_id' => $fuelType->id,
            'city_id' => $city->id,
            'address' => '123 Main St',
            'phone' => '1234567890',
            'description' => 'A great car!',
            'published_at' => true,
        ];
        
        $featuresData = [
            'abs' => true,
            'air_conditioning' => true,
            'power_windows' => true,
            'power_door_locks' => true,
            'cruise_control' => true,
            'bluetooth_connectivity' => true,
            'remote_start' => false,
            'gps_navigation' => true,
            'heated_seats' => false,
            'climate_control' => true,
            'rear_parking_sensors' => false,
            'leather_seats' => true,
        ];
        
        $imagesData = [
            'images' => $images,
        ];
        
        $data = array_merge($carData, $featuresData, $imagesData);

        $response = $this->actingAs($user)->put(route('car.update', $car), $data);

        $response->assertRedirect(route('car.show', $car));

        $this->assertDatabaseHas('cars', [
            'id' => $car->id, 
            ...Arr::except($carData, ['published_at'])
        ]);

        $this->assertDatabaseHas('car_features', [
            'car_id' => $car->id,
            ...$featuresData
        ]);

        Storage::disk('public')->assertMissing('cars/image1.jpg');
        $this->assertDatabaseMissing('car_images', [
            'car_id' => $car->id,
            'image_path' => 'cars/image1.jpg',
            'position' => 0,
        ]);

        foreach ($images as $key => $image) {

            $newPath = 'cars/' . basename($image);
            Storage::disk('public')->assertExists($newPath);

            $this->assertDatabaseHas('car_images', [
                'car_id' => $car->id,
                'image_path' => $newPath,
                'position' => $key,
            ]);
        }

    }    
    
    public function test_user_can_destroy_car()
    {
        Storage::fake('public');

        $this->prepareCarData();

        $user = User::factory()->create();

        $car = Car::factory()->create(['user_id' => $user->id]);
        CarFeature::factory()->create(['car_id' => $car->id]);
        $car->images()->create([
            'image_path' => 'cars/image1.jpg',
            'position' => 0,
        ]);

        $response = $this->actingAs($user)->delete(route('car.destroy', $car));

        $response->assertRedirect(route('car.index'));

        Storage::disk('public')->assertMissing('cars/image1.jpg');

        $this->assertDatabaseMissing('cars', [
            'id' => $car->id,
        ]);
        $this->assertDatabaseMissing('car_features', [
            'car_id' => $car->id,
        ]);
        $this->assertDatabaseMissing('car_images', [
            'car_id' => $car->id,
            'image_path' => 'cars/image1.jpg',
            'position' => 0,
        ]);
    }

    public function test_user_can_access_watchlist_page()
    {
        $user = User::factory()->create();
        
        $this->prepareCarData();
        $car = Car::factory()->create();
        // CarFeature::factory()->create(['car_id' => $car->id]);
        $user->favoriteCars()->attach($car);

        $response = $this->actingAs($user)->get(route('car.watchlist'));

        $response->assertStatus(200);

        $response->assertViewIs('car.watchlist');
        // $response->assertViewHas('cars');
        $response->assertViewHas('cars', function ($cars) use ($car) {
            return $cars->count() === 1 && $cars->first()->is($car);
        });
        $response->assertSee('My Favourite Cars');
    }

    public function test_user_can_toggle_favorite_car_in_watchlist()
    {
        $user = User::factory()->create();
        
        $this->prepareCarData();
        $car = Car::factory()->create();
        // CarFeature::factory()->create(['car_id' => $car->id]);
        // $user->favoriteCars()->attach($car);

        $response = $this->actingAs($user)->post(route('favorite.toggle', $car));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'added']);
        $this->assertDatabaseHas('favourite_cars', [
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);

        $response = $this->actingAs($user)->post(route('favorite.toggle', $car));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'removed']);

        $this->assertDatabaseMissing('favourite_cars', [
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);
    }
}
