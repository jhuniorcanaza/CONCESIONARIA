<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarSearchRequest extends FormRequest
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
            'maker_id' => 'nullable|integer', //|exists:makers,id
            'model_id' => 'nullable|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'car_type_id' => 'nullable|integer',
            'fuel_type_id' => 'nullable|integer',
            'price_from' => 'nullable|integer|min:0',
            'price_to' => 'nullable|integer|min:0',
            'year_from' => 'nullable|integer|min:1900|max:' . date('Y'),
            'year_to' => 'nullable|integer|min:1900|max:' . date('Y'),
            'mileage' => 'nullable|integer|min:0',
            'sort' => 'nullable|string|in:price_asc,price_desc,price,-price',
        ];        
    }

    public function safeData(): array
    {
        return $this->safe()->all(); 
    }    
}
