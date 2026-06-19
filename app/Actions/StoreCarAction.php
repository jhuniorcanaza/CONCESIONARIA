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

                $user = auth()->user();

                if (!$user->isAdmin()) {
                    $approvedCount = $user->cars()->where('is_approved', true)->count();
                    if ($approvedCount >= 2) {
                        return new ActionResult(
                            success: false,
                            message: 'Has alcanzado el límite de 2 publicaciones aprobadas. Contacta al administrador.'
                        );
                    }
                }

                $carData = Arr::only($data, [
                    'maker_id', 'model_id', 'year', 'price', 'vin', 'mileage',
                    'car_type_id', 'fuel_type_id', 'city_id', 'address', 'phone',
                    'description', 'published_at',
                    'body_type', 'drive_type', 'engine_cc', 'bike_type',
                    'start_type', 'road_type', 'machinery_type',
                ]);

                $carData['is_approved'] = $user->isAdmin() ? true : false;

                $car = $user->cars()->create($carData);

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

                CarCreated::dispatch($car);

                $msg = $user->isAdmin()
                    ? 'Anuncio creado y publicado exitosamente'
                    : 'Anuncio creado exitosamente. Está pendiente de aprobación.';

                return new ActionResult(
                    success: true,
                    message: $msg,
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
