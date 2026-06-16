<?php

namespace App\Actions;

use App\DTO\ActionResult;
use App\Events\CarCreated;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreCarAction
{

    public function handle(array $data): ActionResult
    {
        try {
            return DB::transaction(function() use ($data): ActionResult {
                
                $carData = Arr::only($data, [
                    'maker_id', 'model_id', 'year', 'price', 'vin', 'mileage',
                    'car_type_id', 'fuel_type_id', 'city_id', 'address', 'phone',
                    'description', 'published_at']
                );

                // Moved to BoolToDatetimeCast
                // $carData['published_at'] = !empty($carData['published_at']) ? now() : null;
                
                $car = auth()->user()->cars()->create($carData);
                

                $featuresData = Arr::only($data, [
                    'abs', 'air_conditioning', 'power_windows', 'power_door_locks',
                    'cruise_control', 'bluetooth_connectivity', 'remote_start',
                    'gps_navigation', 'heated_seats', 'climate_control',
                    'rear_parking_sensors', 'leather_seats'
                ]);
                
                $car->features()->create($featuresData);        

                foreach ($data['images'] ?? [] as $key => $tempPath) {
                    $path = 'cars/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $path); 

                    $car->images()->create([
                        'image_path' => $path,
                        'position' => $key
                    ]);
                }
                
                // if ($request->hasFile('images')) {
                //     foreach ($validated['images'] as $key => $image) {
                //         $path = $image->store('cars', 'public');
                //         $car->images()->create([
                //             'image_path' => $path,
                //             'position' => $key
                //         ]);
                //     }
                // }        

                CarCreated::dispatch($car);

                return new ActionResult(
                    success: true,
                    message: 'Car was successfully added',
                    data: $car
                );
            });

        } catch (\Exception $e) {

            Log::error('Error while creating car: ' . $e->getMessage());

            return new ActionResult(
                success: false,
                message: 'Failed to create the car'
            );
        }          
        
    }    
}
