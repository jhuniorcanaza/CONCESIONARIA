Laravel 11 in 11 hours - Laravel for Beginners Full Course
https://www.youtube.com/watch?v=0M84Nk7iWkA

@ToDo
+1. create job and add to schedule 
Storage::disk('public')->deleteOlderThan('temp', now()->subHours(6));

+2. edit user

+3. google login + facebook login

+4. show phone on the car page using ajax

+5. redirect after login to the prev page

+6. send mails for register user (? what else)

+7. event listeners for some tasks like creation new car (? what else)

+8. add avatar to user 

+9. create new Task for delete new users cars and data

10. look at all code

11. remove all demo data

12. create actual users and data

13. deploy on the server

14. test everything



lt --port 8001 --subdomain svlaracar
https://loca.lt/mytunnelpassword
188.163.121.66

https://chatgpt.com/c/67e04674-aa4c-8012-a68f-3530235cfb3c
https://www.youtube.com/watch?v=S-y1ZI0INlo&ab_channel=ProgrammingFields
composer require laravel/socialitecomposer require laravel/socialite

https://console.cloud.google.com/auth/clients?inv=1&invt=Abs0Vg&project=youtubeapi-398818

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8001/auth/google/callback

FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT_URI=http://localhost:8001/auth/facebook/callback


https://svlaracar.loca.lt/auth/facebook/callback


php artisan config:clear
php artisan route:clear
php artisan cache:clear

Artisan commands
composer create-project --prefer-dist laravel/laravel:^11.0 larajob_traversy

php artisan about
php artisan list
php artisan inspire
php artisan make help
php artisan config:publish
dump($var); dd(), ddd();
to_route($route);
Route::fallback(function(){})
php artisan route:list -v
php artisan route:cache

php artisan make:controller CarController
php artisan make:controller CarController --resource
php artisan make:component BaseLayout

4:51:00 - DB Schema

//php artisan make:migration create_users_table
php artisan make:migration create_favourite_cars_table
php artisan make:migration create_car_types_table
php artisan make:migration create_states_table
php artisan make:migration create_cities_table
php artisan make:migration create_makers_table
php artisan make:migration create_models_table
php artisan make:migration create_fuel_types_table
php artisan make:migration create_cars_table
php artisan make:migration create_car_features_table
php artisan make:migration add_social_fields_to_users_table --table=users

php artisan list migrate
php artisan migrate:status
php artisan migrate:pretend
php artisan migrate:rollback --step=3
php artisan migrate:rollback --batch=2
php artisan migrate:fresh

php artisan migrate --path=database/migrations/2025_03_06_110126_add_social_fields_to_users_table.php

php artisan make:model FavouriteCar &&
php artisan make:model CarType &&
php artisan make:model State &&
php artisan make:model City &&
php artisan make:model Maker &&
php artisan make:model CarModel &&
php artisan make:model FuelType &&
php artisan make:model Car &&
php artisan make:model CarFeature &&
php artisan make:model CarImage

php artisan make:model CarImage -mc //migration + controller
php artisan model:show CarImage

// Create
$car = new Car();
$car->field = "";
$car->save();

Car::create($carData); // fillable
or
$car = new Car();
$car->fill($carData);
$car->save();
or
$car = new Car($carData);
$car->save();

// Update 
$car = Car::find(1); // id=1
$car->price = 111;
$car->save();

Car::updateOrCreate([
  'vin' => '999', 'price' => 1000
],[
  'price' => 2000
]
);

Car::where('published_at', null)
  ->where('user_id', 1)
  ->update('published_at', now())

// Delete
$car = Car::find(1); // id=1
$car->delete();
or
$car = Car::destroy([1, 2, 3]);
or
Car::where('published_at', null)
  ->where('user_id', 1)
  ->delete()
or
Car::truncate(); //delete all records with soft deletes!

Relations
\App\Models\Car::find(1)->features; //hasOne

$car = Car::find(1);
$car->features->abs = 1;
$car->features->save();
$car->features->update(['abs' => 1]);
$car->features->delete();

$car_features = new \App\Models\CarFeature(['abs' => 1,    'air_conditioning' => 0,    'power_windows' => 1,    'power_door_locks' => 1,    'cruise_control' => 0,    'bluetooth_connectivity' => 0,    'remote_start' => 1,    'gps_navigation' => 0,    'heated_seats' => 1,    'climate_control' => 1,    'rear_parking_sensors' => 0,    'leather_seats' => 1]);

\App\Models\Car::find(2)->features()->save($car_features);
\App\Models\Car::find(2)->features;

\App\Models\Car::find(1)->images->get(0)->id // hasMany
$car_image = new \App\Models\CarImage(['image_path' => 'images/iamge3.jpg', 'position' => 3])
\App\Models\Car::find(1)->images()->save($car_image);
\App\Models\Car::find(1)->images()->saveMany([$car_image, $car_image]);
\App\Models\Car::find(1)->images()->create(['image_path' => 'images/iamge4.jpg', 'position' => 4]);
\App\Models\Car::find(1)->images()->createMany([
  ['image_path' => 'images/iamge4.jpg', 'position' => 4],
  ['image_path' => 'images/iamge4.jpg', 'position' => 4],
]);

//belongsTo

$carType = \App\Models\CarType::where('name', 'SUV')->first();
\App\Models\Car::whereBelongsTo($carType)->get();

$cartType->cars;

$car->car_type_id = $carType->id;
or
$car->carType()->assosiate($carType)
$cart->save();

//belongsToMany (many to many relation)
\App\Models\Car::find(1)->favoriteUsers;
\App\Models\Car::find(1)->favoriteUsers->first()->pluck('name');

\App\Models\User::find(1)->favoriteCars;
\App\Models\User::find(1)->favoriteCars->pluck('id');
\App\Models\User::find(1)->favoriteCars()->attach([3]);
\App\Models\User::find(1)->favoriteCars()->detach([3]); // remove
\App\Models\User::find(1)->favoriteCars()->detach(); // remove all
\App\Models\User::find(1)->favoriteCars()->sync([2,3]); //delete old+create new

// Factories
php artisan make:factory MakerFactory 
php artisan db:seed
php artisan make:factory ModelFactory

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

php artisan make:seeder UsersSeeder
php artisan db:seed --class=UsersSeeder
// Не можно запускати на продакшині! APP_ENV=local - можна
// або дати --force тоді можна запустити і на продакшині

php artisan migrate --seed //записить дефолтний сідер (DatabaseSeeder.php)
php artisan migrate:fresh --seed

php artisan db:wipe
php artisan db:seed

composer require barryvdh/laravel-debugbar --dev
APP_DEBUG=true
DEBUGBAR_ENABLED=true

php artisan config:clear
php artisan cache:clear

AppServiceProvider
Paginator::defaultView('pagination'); 
or
php artisan vendor:publish --tag=laravel-pagination

php artisan make:job ClearTempFolder

php artisan schedule:list
php artisan schedule:run
php artisan schedule:test - run every scheduled task immidiately  

php artisan make:command RunClearTempFolder
php artisan run:clear-temp

==============
FROM LaraJob project

composer create-project --prefer-dist laravel/laravel:^10.0 larajob_traversy

php artisan make:migration create_listings_table
php artisan migrate
php artisan db:seed
php artisan migrate:refresh
php artisan migrate:refresh --seed
php artisan make:migration add_logo_to_listings_table --table=listings
php artisan migrate
php artisan migrate:rollback

php artisan make:model Listing
php artisan make:factory ListingFactory
php artisan make:view layout
php artisan make:controller ListingController

composer require itsgoingd/clockwork

php artisan route:list
php artisan route:cache

php artisan route:clear
php artisan config:clear
php artisan view:clear

php artisan vendor:publish

php artisan storage:link

php artisan make:migration add_user_id_to_listings_table --table=listings

php artisan tinker
  \App\Models\Listing::first()->user
  \App\Models\Listing::find(3)
  $user = \App\Models\User::first()
  $user->listings

php artisan make:middleware EnsureUserOwnsListing

