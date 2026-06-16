@props(['type' => 'horisontal'])
{{-- @dd(route('car.search')) --}}
@if($type == 'horisontal')
    <form
        id="searchCarForm"
        action="{{ route('car.search') }}"
        method="GET"
        class="find-a-car-form card flex p-medium"
    >
        <div class="find-a-car-inputs">
            <div>
                <x-ui.search-select id="makerSelect" name="maker_id" :elements="$makers" title="Maker" />            
            </div>
            <div>
                <x-ui.search-select id="modelSelect" name="model_id" :elements="$models" title="Model" parent="maker_id"/>            
            </div>
            <div>
                <x-ui.search-select id="stateSelect" name="state_id" :elements="$states" title="State/Region"/>                
            </div>
            <div>
                <x-ui.search-select id="citySelect" name="city_id" :elements="$cities" title="City" parent="state_id"/>            
            </div>
            <div>
                <x-ui.search-select id="carTypeSelect" name="car_type_id" :elements="$types" title="Type"/>                        
            </div>
            <div>
                <input type="number" placeholder="Year From" name="year_from" />
            </div>
            <div>
                <input type="number" placeholder="Year To" name="year_to" />
            </div>
            <div>
                <input type="number" placeholder="Price From" name="price_from" />
            </div>
            <div>
                <input type="number" placeholder="Price To" name="price_to" />
            </div>
            <div>
                <x-ui.search-select id="fuelTypeSelect" name="fuel_type_id" :elements="$fuelTypes" title="Fuel Type"/>            
            </div>
        </div>

        <div>
            <button type="button" id="resetSearchForm" class="btn btn-find-a-car-reset"> Reset </button>
            <button type="submit" class="btn btn-primary btn-find-a-car-submit"> Search </button>        
        </div>
    </form>
@else
    <form 
        id="searchCarForm"
        action="{{ route('car.search') }}"
        method="GET"
        class="find-a-car-form card flex p-medium"
    >
        <div class="find-a-car-inputs">
            <div class="form-group">
                <label class="mb-medium">Maker</label>
                <x-ui.search-select id="makerSelect" name="maker_id" :elements="$makers" title="Maker" :filtered="$filtered['maker_id'] ?? null" />
            </div>
            <div class="form-group">
                <label class="mb-medium">Model</label>
                <x-ui.search-select id="modelSelect" name="model_id" :elements="$models" title="Model" parent="maker_id" :filtered="$filtered['model_id'] ?? null" />
            </div>
            <div class="form-group">
                <label class="mb-medium">Type</label>
                <x-ui.search-select id="carTypeSelect" name="car_type_id" :elements="$types" title="Type"  :filtered="$filtered['car_type_id'] ?? null" />
            </div>
            <div class="form-group">
                <label class="mb-medium">Year</label>
                <div class="flex gap-1">
                <input type="number" placeholder="Year From" name="year_from" value="{{ $filtered['year_from'] ?? null }}"/>
                <input type="number" placeholder="Year To" name="year_to" value="{{ $filtered['year_to'] ?? null }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="mb-medium">Price</label>
                <div class="flex gap-1">
                <input type="number" placeholder="Price From" name="price_from" value="{{ $filtered['price_from'] ?? null }}" />
                <input type="number" placeholder="Price To" name="price_to" value="{{ $filtered['price_to'] ?? null }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="mb-medium">Mileage</label>
                <div class="flex gap-1">
                {{-- <x-ui.search-select id="mileage" name="mileage" :elements="$milage" title="Any Mileage"/>   --}}
                <select name="mileage">
                    <option value="">Any Mileage</option>
                    @foreach ($milage as $item)
                        <option 
                            value="{{ $item['id'] }}" 
                            @if(isset($filtered['mileage']) && $filtered['mileage'] == $item['id']) selected @endif
                        >{{ $item['name'] }}</option>                        
                    @endforeach
                </select>                
                </div>
            </div>
            <div class="form-group">
                <label class="mb-medium">State</label>
                <x-ui.search-select id="stateSelect" name="state_id" :elements="$states" title="State/Region"  :filtered="$filtered['state_id'] ?? null" />
            </div>
            <div class="form-group">
                <label class="mb-medium">City</label>
                <x-ui.search-select id="citySelect" name="city_id" :elements="$cities" title="City" parent="state_id"  :filtered="$filtered['city_id'] ?? null" />
            </div>
            <div class="form-group">
                <label class="mb-medium">Fuel Type</label>
                <x-ui.search-select id="fuelTypeSelect" name="fuel_type_id" :elements="$fuelTypes" title="Fuel Type"  :filtered="$filtered['fuel_type_id'] ?? null" />
            </div>
        </div>
        <div class="flex">
            <button type="button" id="resetSearchForm" class="btn btn-find-a-car-reset"> Reset </button>
            <button type="submit" class="btn btn-primary btn-find-a-car-submit"> Search </button>
        </div>
    </form>
@endif
