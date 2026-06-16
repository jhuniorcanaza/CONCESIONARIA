<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'maker_id' => ['required', 'exists:makers,id'],
            'model_id' => ['required', 'exists:models,id'],
            'year' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'price' => ['required', 'integer', 'min:0'],
            'vin' => ['required', 'string', 'max:255',
                Rule::unique('cars', 'vin')
                    ->ignore($this->car)
            ],
            'mileage' => ['required', 'integer', 'min:0'],
            'car_type_id' => ['required', 'exists:car_types,id'],
            'fuel_type_id' => ['required', 'exists:fuel_types,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:45'],
            'description' => ['nullable', 'string'],
            'published_at' => ['boolean'],
            
            'abs' => ['boolean'],
            'air_conditioning' => ['boolean'],
            'power_windows' => ['boolean'],
            'power_door_locks' => ['boolean'],
            'cruise_control' => ['boolean'],
            'bluetooth_connectivity' => ['boolean'],
            'remote_start' => ['boolean'],
            'gps_navigation' => ['boolean'],
            'heated_seats' => ['boolean'],
            'climate_control' => ['boolean'],
            'rear_parking_sensors' => ['boolean'],
            'leather_seats' => ['boolean'],            
            
            'images' => ['required', 'array', 'max:10'],
            // 'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'images.*' => ['string'], // Dropzone.js will send the image as a path
        ];
    }
}
