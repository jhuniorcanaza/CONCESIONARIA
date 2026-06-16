<?php

namespace App\Actions;

use App\DTO\ActionResult;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class DestroyCarAction
{
    /**
     * Create a new class instance.
     */
    public function handle(Car $car): ActionResult
    {
        try {
            return DB::transaction(function() use ($car): ActionResult {
                // car images are deleted by car model deleting event, image files are deleted by image model deleting event
                // car features and favorite cars are deleted by foreign key constraints
                $car->delete();
                return new ActionResult(true, 'Car was successfully deleted');
            });            
        } catch (\Exception $e) {
            return new ActionResult(false, 'Failed to delete the car');            
        }               
    }
}
