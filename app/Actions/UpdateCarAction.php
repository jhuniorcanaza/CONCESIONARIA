<?php

namespace App\Actions;

use App\DTO\ActionResult;
use App\Models\Car;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateCarAction
{
    public function handle(array $data, Car $car): ActionResult
    {
        try {
            return DB::transaction(function() use ($data, $car): ActionResult {

                $carData = Arr::only($data, [
                    'maker_id', 'model_id', 'year', 'price', 'vin', 'mileage',
                    'car_type_id', 'fuel_type_id', 'city_id', 'address', 'phone',
                    'description', 'published_at']
                );

                // Moved to BoolToDatetimeCast
                // $carData['published_at'] = !empty($carData['published_at']) ? now() : null;
                                
                $car->update($carData);
                

                $featuresData = Arr::only($data, [
                    'abs', 'air_conditioning', 'power_windows', 'power_door_locks',
                    'cruise_control', 'bluetooth_connectivity', 'remote_start',
                    'gps_navigation', 'heated_seats', 'climate_control',
                    'rear_parking_sensors', 'leather_seats'
                ]);
                
                $car->features()->update($featuresData);        

                $existingImages = $car->images;  
                
                $newImages = [];
                $updatedImages = [];
                
                foreach ($data['images'] as $key => $tempPath) {
                    // new images uploaded by client - move them from temp to cars folder
                    if (Str::startsWith($tempPath, 'temp')) {
                        $path = 'cars/' . basename($tempPath);
                        Storage::disk('public')->move($tempPath, $path);
                        $newImages[] = ['image_path' => $path, 'position' => $key];
                    } else {
                        $updatedImages[$tempPath] = $key;
                    }
                }
                
                // create new images in DB
                if (!empty($newImages)) {
                    $car->images()->createMany($newImages);
                }
                
                // update positions of existing images
                foreach ($updatedImages as $path => $position) {
                    $car->images()->where('image_path', $path)->update(['position' => $position]);
                }

                // delete images from DB what were deleted by client in browser
                $imagesToDelete = $existingImages->whereNotIn('image_path', array_keys($updatedImages));
                foreach ($imagesToDelete as $image) {
                    // Storage::disk('public')->delete($image->image_path);
                    // files are deleted by model event
                    $image->delete();
                }
                
                return new ActionResult(true, 'Car updated successfully'); 
           });

        } catch (\Exception $e) {

            Log::error('Error while editing car: ' . $e->getMessage());
            
            return new ActionResult(false, 'Failed to update car');
        }          
    }
}
