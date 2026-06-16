<x-app-layout title="Create new car">
  <main>
    <div class="container-small">

      <h1 class="car-details-page-title">Edit car</h1>

      <form
        id="carForm"
        action="{{ route('car.update', $car->id) }}"
        method="POST"
        enctype="multipart/form-data"
        class="card add-new-car-form"
      >
        @csrf
        @method('PUT')
        
        {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif --}}

        <div class="form-content">
          <div class="form-details">

            <div class="row">
              <div class="col">
                <x-ui.form-group name="maker_id" label="Maker">
                  <x-ui.search-select id="makerSelect" name="maker_id" :elements="$makers" title="Maker" :filtered="old('maker_id', $car->maker_id)" />
                </x-ui.form-group>
              </div>

              <div class="col">
                <x-ui.form-group name="model_id" label="Model">
                  <x-ui.search-select id="modelSelect" name="model_id" :elements="$models" title="Model" parent="maker_id" :filtered="old('model_id', $car->model_id)" />
                </x-ui.form-group>
              </div>

              <div class="col">
                <x-ui.form-group name="year" label="Year">
                  <input type="number" name="year" placeholder="Year" value="{{ old('year', $car->year) }}"/>
                </x-ui.form-group>
              </div>
            </div>

            <x-ui.form-group name="car_type_id" label="Car Type">
              <x-ui.inline-radio name="car_type_id" :elements="$types" :selected="old('car_type_id', $car->car_type_id)" />
            </x-ui.form-group>    

            <div class="row">
              <div class="col">
                <x-ui.form-group name="price" label="Price">
                  <input type="number" name="price" placeholder="Price" value="{{ old('price', $car->price) }}"/>
                </x-ui.form-group>
              </div>
              <div class="col">
                <x-ui.form-group name="vin" label="Vin Code">
                  <input name="vin" placeholder="Vin Code" value="{{ old('vin', $car->vin) }}"/>
                </x-ui.form-group>
              </div>
              <div class="col">
                <x-ui.form-group name="mileage" label="Mileage">
                  <input name="mileage" placeholder="Mileage" value="{{ old('mileage', $car->mileage) }}"/>
                </x-ui.form-group>
              </div>
            </div>
            <x-ui.form-group name="fuel_type_id" label="Fuel Type">
              <x-ui.inline-radio name="fuel_type_id" :elements="$fuelTypes" :selected="old('fuel_type_id', $car->fuel_type_id)" />
            </x-ui.form-group>            

            <div class="row">
              <div class="col">
                <x-ui.form-group name="state_id" label="State/Region">
                  <x-ui.search-select id="stateSelect" name="state_id" :elements="$states" title="State/Regio" :filtered="old('state_id', $car->city->state_id)" />
                </x-ui.form-group>                
              </div>
              <div class="col">
                <x-ui.form-group name="city_id" label="City">
                  <x-ui.search-select id="citySelect" name="city_id" :elements="$cities" title="City" parent="state_id" :filtered="old('city_id', $car->city_id)" />
                </x-ui.form-group>                
              </div>
            </div>

            <div class="row">
              <div class="col">
                <x-ui.form-group name="address" label="Address">
                  <input name="address" placeholder="Address" value="{{ old('address', $car->address) }}"/>
                </x-ui.form-group>
              </div>

              <div class="col">
                <x-ui.form-group name="phone" label="Phone">
                  <input name="phone" placeholder="Phone" value="{{ old('phone', $car->phone) }}"/>
                </x-ui.form-group>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <x-ui.checkbox name="air_conditioning" label="Air Conditioning" :selected="old('air_conditioning', $car->features->air_conditioning)" />
                  <x-ui.checkbox name="power_windows" label="Power Windows" :selected="old('power_windows', $car->features->power_windows)" />
                  <x-ui.checkbox name="power_door_locks" label="Power Door Locks" :selected="old('power_door_locks', $car->features->power_door_locks)" />
                  <x-ui.checkbox name="abs" label="ABS" :selected="old('abs', $car->features->abs)" />
                  <x-ui.checkbox name="cruise_control" label="Cruise Control" :selected="old('cruise_control', $car->features->cruise_control)" />
                  <x-ui.checkbox name="bluetooth_connectivity" label="Bluetooth Connectivity" :selected="old('bluetooth_connectivity', $car->features->bluetooth_connectivity)" />
                </div>
                <div class="col">
                  <x-ui.checkbox name="remote_start" label="Remote Start" :selected="old('remote_start', $car->features->remote_start)" />
                  <x-ui.checkbox name="gps_navigation" label="GPS Navigation System" :selected="old('gps_navigation', $car->features->gps_navigation)" />
                  <x-ui.checkbox name="heated_seats" label="Heated Seats" :selected="old('heated_seats', $car->features->heated_seats)" />
                  <x-ui.checkbox name="climate_control" label="Climate Control" :selected="old('climate_control', $car->features->climate_control)" />
                  <x-ui.checkbox name="rear_parking_sensors" label="Rear Parking Sensors" :selected="old('rear_parking_sensors', $car->features->rear_parking_sensors)" />
                  <x-ui.checkbox name="leather_seats" label="Leather Seats" :selected="old('leather_seats', $car->features->leather_seats)" />
                </div>
              </div>
            </div>

            <x-ui.form-group name="description" label="Detailed Description">
              <textarea name="description" rows="10">{{ old('description', $car->description) }}</textarea>
            </x-ui.form-group>

            <div class="form-group">
              <x-ui.checkbox name="published_at" label="Published" :selected="old('published_at', (bool)$car->published_at)" />
            </div>

          </div>

          <div class="form-images">

            <x-ui.form-group name="images" label="Images">
              <x-image-upload :images="$car->images ?? []"/>
            </x-ui.form-group>            

          </div>
        </div>
        <div class="p-medium" style="width: 100%">
          <div class="flex justify-end gap-1">
            <button id="resetCarForm" type="button" class="btn btn-default">Reset</button>
            <button class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </main>
</x-app-layout>