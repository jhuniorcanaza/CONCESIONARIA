<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarFeature;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User', // override the default name
        //     'email' => 'test@example.com', //override the default email
        // ]);

        // User::factory()->sequence([ // поочередное создание  
        //     ['name' => 'One'], 
        //     ['name' => 'Two'], 
        // );

        // User::factory()->count(3)->unverified()->create(); // with state unverified (look at UserFactory.php)
        // User::factory()->count(3)->trashed()->create(); // with state trashed (deleted_at is not null)
        // User::factory()->afterCreating(function (User $user) { // after creating user
        //     $user->assignRole('admin');
        // })->create();


        // Maker::factory(5)->create();
        
        // has many relation
        // Maker::factory(5)->hasModels(3)->create(); // create 5 makers with 3 models each
        // Maker::factory(5)->hasModels(3, ['name' => "Test"])->create(); 
        // Maker::factory(5)->hasModels(3, function($attributes, Maker $maker) {
        //     return $maker->name === 'Test' ? ['name' => 'Test'] : []; 
        // })->create();
        // Maker::factory(5)->has(Model::class ....)->create();

        // belongs to relations
        // Model::factory()->count(10)->forMaker(['name' => 'Lexus'])->create();
        // Model::factory()->count(10)->for(Maker::factory()->state(['name' => 'Lexus']))->create();

        // Many to many relations Create user with 5 cars (and random user_id) and add them to favorite cars
        // User::factory()
        // ->has(Car::factory()->count(5), 'favoriteCars')
        // ->create();


        // запуск других сидеров
        // $this->call([UsersSeeder::class]);

        // Actualizar tipos de vehículo a los 3 requeridos
        $typeNames = ['Auto', 'Motocicleta', 'Maquinaria Pesada'];
        foreach ($typeNames as $i => $name) {
            CarType::updateOrCreate(['id' => $i + 1], ['name' => $name]);
        }
        // Reasignar autos con tipos antiguos (4-7) a Auto
        Car::where('car_type_id', '>', 3)->update(['car_type_id' => 1]);
        // Eliminar tipos sobrantes
        CarType::where('id', '>', 3)->delete();

        FuelType::factory()
            ->sequence(
                ['name' => 'Gas'], 
                ['name' => 'Diesel'], 
                ['name' => 'Electric'], 
                ['name' => 'Hybrid'], 
            )
            ->count(4)
            ->create();

        // $states = [
        //     'Alaska' => ['Anchorage', 'Juneau', 'Fairbanks', 'Sitka', 'Ketchikan'],
        //     'Texas' => ['Houston', 'San Antonio', 'Dallas', 'Austin', 'Fort Worth'],
        //     'California' => ['Los Angeles', 'San Diego', 'San Jose', 'San Francisco', 'Fresno'],
        //     'Montana' => ['Billings', 'Missoula', 'Great Falls', 'Bozeman', 'Butte'],
        //     'New Mexico' => ['Albuquerque', 'Las Cruces', 'Rio Rancho', 'Santa Fe', 'Roswell'],
        //     'Arizona' => ['Phoenix', 'Tucson', 'Mesa', 'Chandler', 'Scottsdale'],
        //     'Nevada' => ['Las Vegas', 'Henderson', 'Reno', 'North Las Vegas', 'Sparks'],
        //     'Colorado' => ['Denver', 'Colorado Springs', 'Aurora', 'Fort Collins', 'Lakewood'],
        //     'Oregon' => ['Portland', 'Eugene', 'Salem', 'Gresham', 'Hillsboro'],
        //     'Wyoming' => ['Cheyenne', 'Casper', 'Laramie', 'Gillette', 'Rock Springs'],
        // ];            
        
        $states = [
            
            'La Paz' => ['El Alto', 'Viacha', 'Caranavi', 'Coripata','Chulumani', 'Sorata', 'Achocalla', 'Laja', 'Mecapaca'],
            'Santa Cruz' => ['Montero', 'Warnes', 'La Guardia','Cotoca', 'San Ignacio de Velasco', 'Roboré', 'Camiri','San José de Chiquitos', 'Pailón'],
            'Cochabamba' => ['Quillacollo', 'Mizque','Sacaba', 'Cliza', 'Tiquipaya', 'Colcapirhua', 'Punata', 'Arani'],
            'Oruro' => ['Huanuni', 'Machacamarca', 'Challapata','Caracollo', 'Poopó', 'Eucaliptus'],
            'Potosí' => ['Uyuni', 'Villazón', 'Tupiza', 'Betanzos', 'Colquechaca', 'Puna'],
            'Tarija' => ['Yacuiba', 'Villamontes', 'Bermejo','Entre Ríos', 'Caraparí', 'Uriondo'],
            'Chuquisaca' => ['Sucre', 'Monteagudo', 'Camargo', 'Azurduy','Tarabuco', 'Padilla', 'Villa Serrano', 'Tomina'],
            'Beni' => ['Trinidad', 'Santa Ana del Yacuma', 'Riberalta','Guayaramerín', 'Rurrenabaque', 'San Borja', 'Reyes', 'Santa Rosa'],
            'Pando' => ['Cobija', 'Porvenir', 'Puerto Rico', 'Bella Vista','Santa Rosa del Abuná', 'Filadelfia', 'Villa Busch'],
            
        ];

        foreach ($states as $state => $cities) {
            State::factory()
                ->state(['name' => $state])
                ->has(City::factory()
                    ->count(count($cities))
                    ->sequence(...array_map(function ($city) {
                        return ['name' => $city];
                    }, $cities))
                )
                ->create();
        }

        $makers = [
            'Toyota' => ['Camry', 'Corolla', 'Highlander', 'RAV4', 'Prius', 'Land Cruiser', 'Supra'],
            'Ford' => ['F-150', 'Escape', 'Explorer', 'Mustang', 'Fusion', 'Bronco', 'Focus'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey', 'HR-V', 'Fit'],
            'Chevrolet' => ['Silverado', 'Equinox', 'Malibu', 'Impala', 'Tahoe', 'Camaro', 'Suburban'],
            'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Maxima', 'Murano', 'Pathfinder', 'Frontier'],
            'Lexus' => ['RX400', 'RX450', 'RX350', 'ES350', 'LS500', 'IS300', 'NX200'],
            'BMW' => ['X5', 'X3', 'X7', 'M3', 'M5', '330i', 'Z4']
        ];
        
        foreach ($makers as $maker => $models) {
            Maker::factory()
                ->state(['name' => $maker])
                ->has(Model::factory()
                    ->count(count($models))
                    ->sequence(...array_map(function ($model) {
                        return ['name' => $model];
                    }, $models))
                )
                ->create();
        }


        // Admin user
        if (!\App\Models\User::where('email', 'admin@ruedas.store')->exists()) {
            User::factory()
                ->state(['name' => 'Admin', 'email' => 'admin@ruedas.store', 'role' => 'admin'])
                ->has(
                    Car::factory()->count(rand(5, 20))
                        ->hasFeatures()
                        ->has(
                            CarImage::factory()
                                ->count(5)
                                ->sequence(...array_map(function ($i) {
                                    return ['position' => $i];
                                    }, range(1, 5))
                                ),
                            'images'
                        ),
                    'favoriteCars')
                ->create();
        }

        User::factory()
            ->count(10)
            ->has(
                Car::factory()->count(rand(5, 20))
                    ->hasFeatures()
                    ->has(
                        CarImage::factory()
                            ->count(5)
                            ->sequence(...array_map(function ($i) {
                                return ['position' => $i];
                                }, range(1, 5))
                            ),
                        'images'
                    ),
                'favoriteCars')
            ->create();

        User::factory()
            ->count(10)
            ->has(
                Car::factory()->count(rand(5, 20))
                    // ->has(
                    //     CarFeature::factory()->count(1)
                    // )
                    ->hasFeatures()
                    ->has(
                        CarImage::factory()
                            ->count(5)
                            // ->sequence(fn(Sequence $sequance) =>
                            //     ['position' => $sequance->index%5+1])
                            ->sequence(...array_map(function ($i) {
                                return ['position' => $i];
                                }, range(1, 5))
                            ),
                        'images'    
                    ), 
                'favoriteCars')
            ->create();

        Car::inRandomOrder()->limit(5)->update(['is_featured' => true]);
        Car::inRandomOrder()->limit(5)->update(['show_in_carousel' => true]);
    }
}
