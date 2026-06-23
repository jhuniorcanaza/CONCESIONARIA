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
                    'description', 'published_at', 'is_featured', 'show_in_carousel',
                    'body_type', 'drive_type', 'engine_cc', 'bike_type',
                    'start_type', 'road_type', 'machinery_type',
                ]);

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
                    if (Str::startsWith($tempPath, 'temp')) {
                        $path = 'cars/' . basename($tempPath);
                        Storage::disk('public')->move($tempPath, $path);
                        $newImages[] = ['image_path' => $path, 'position' => $key];
                    } else {
                        $updatedImages[$tempPath] = $key;
                    }
                }

                if (!empty($newImages)) {
                    $car->images()->createMany($newImages);
                }

                foreach ($updatedImages as $path => $position) {
                    $car->images()->where('image_path', $path)->update(['position' => $position]);
                }

                $imagesToDelete = $existingImages->whereNotIn('image_path', array_keys($updatedImages));
                foreach ($imagesToDelete as $image) {
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
