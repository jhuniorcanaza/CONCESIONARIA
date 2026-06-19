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
        x-data="{ carTypeId: {{ old('car_type_id', $car->car_type_id) }}, typeNames: {{ json_encode($types->pluck('name', 'id')) }} }"
      >
        @csrf
        @method('PUT')

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

            {{-- Car Type (radio inline con Alpine) --}}
            <x-ui.form-group name="car_type_id" label="Tipo de Vehículo">
              <div class="row">
                @foreach ($types as $type)
                  <div class="col">
                    <label class="inline-radio">
                      <input type="radio" name="car_type_id" value="{{ $type->id }}"
                             {{ old('car_type_id', $car->car_type_id) == $type->id ? 'checked' : '' }}
                             x-on:change="carTypeId = $event.target.value"/>
                      {{ $type->name }}
                    </label>
                  </div>
                @endforeach
              </div>
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
            </div>

            {{-- Mileage / Horas de uso (label dinámico) --}}
            <x-ui.form-group name="mileage" label="Kilometraje (Km)">
              <template x-if="carTypeId && typeNames[carTypeId] === 'Maquinaria Pesada'">
                <span style="display: block; margin-bottom: 4px; font-weight: 500;">Horas de uso</span>
              </template>
              <template x-if="!carTypeId || typeNames[carTypeId] !== 'Maquinaria Pesada'">
                <span style="display: block; margin-bottom: 4px; font-weight: 500;">Kilometraje (Km)</span>
              </template>
              <input name="mileage" placeholder="0" value="{{ old('mileage', $car->mileage) }}"/>
            </x-ui.form-group>

            {{-- Campos dinámicos: Auto --}}
            <template x-if="carTypeId && typeNames[carTypeId] === 'Auto'">
              <div>
                <div class="row">
                  <div class="col">
                    <x-ui.form-group name="body_type" label="Tipo de carrocería">
                      <select name="body_type">
                        <option value="">Seleccionar</option>
                        @foreach ($bodyTypes as $opt)
                          <option value="{{ $opt->id }}" {{ old('body_type', $car->body_type) == $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                      </select>
                    </x-ui.form-group>
                  </div>
                  <div class="col">
                    <x-ui.form-group name="drive_type" label="Tracción">
                      <select name="drive_type">
                        <option value="">Seleccionar</option>
                        @foreach ($driveTypes as $opt)
                          <option value="{{ $opt->id }}" {{ old('drive_type', $car->drive_type) == $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                      </select>
                    </x-ui.form-group>
                  </div>
                </div>
              </div>
            </template>

            {{-- Campos dinámicos: Motocicleta --}}
            <template x-if="carTypeId && typeNames[carTypeId] === 'Motocicleta'">
              <div>
                <div class="row">
                  <div class="col">
                    <x-ui.form-group name="engine_cc" label="Cilindrada (cc)">
                      <input type="number" name="engine_cc" placeholder="Ej. 150, 250, 600" value="{{ old('engine_cc', $car->engine_cc) }}"/>
                    </x-ui.form-group>
                  </div>
                  <div class="col">
                    <x-ui.form-group name="bike_type" label="Tipo de Moto">
                      <select name="bike_type">
                        <option value="">Seleccionar</option>
                        @foreach ($bikeTypes as $opt)
                          <option value="{{ $opt->id }}" {{ old('bike_type', $car->bike_type) == $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                      </select>
                    </x-ui.form-group>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <x-ui.form-group name="start_type" label="Tipo de arranque">
                      <select name="start_type">
                        <option value="">Seleccionar</option>
                        @foreach ($startTypes as $opt)
                          <option value="{{ $opt->id }}" {{ old('start_type', $car->start_type) == $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                      </select>
                    </x-ui.form-group>
                  </div>
                </div>
              </div>
            </template>

            {{-- Campos dinámicos: Maquinaria Pesada --}}
            <template x-if="carTypeId && typeNames[carTypeId] === 'Maquinaria Pesada'">
              <div>
                <div class="row">
                  <div class="col">
                    <x-ui.form-group name="road_type" label="Tipo de Rodado">
                      <select name="road_type">
                        <option value="">Seleccionar</option>
                        @foreach ($roadTypes as $opt)
                          <option value="{{ $opt->id }}" {{ old('road_type', $car->road_type) == $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                      </select>
                    </x-ui.form-group>
                  </div>
                  <div class="col">
                    <x-ui.form-group name="machinery_type" label="Tipo de Maquinaria">
                      <select name="machinery_type">
                        <option value="">Seleccionar</option>
                        @foreach ($machineryTypes as $opt)
                          <option value="{{ $opt->id }}" {{ old('machinery_type', $car->machinery_type) == $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>
                        @endforeach
                      </select>
                    </x-ui.form-group>
                  </div>
                </div>
              </div>
            </template>

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

            {{-- Features (checkboxes) condicionales --}}
            <div class="form-group">
              <h3>Características</h3>

              <template x-if="!carTypeId || typeNames[carTypeId] === 'Auto'">
                <div class="row">
                  <div class="col">
                    <x-ui.checkbox name="air_conditioning" label="Aire Acondicionado" :selected="old('air_conditioning', $car->features->air_conditioning)" />
                    <x-ui.checkbox name="power_windows" label="Vidrios Eléctricos" :selected="old('power_windows', $car->features->power_windows)" />
                    <x-ui.checkbox name="power_door_locks" label="Seguros Eléctricos" :selected="old('power_door_locks', $car->features->power_door_locks)" />
                    <x-ui.checkbox name="abs" label="ABS" :selected="old('abs', $car->features->abs)" />
                    <x-ui.checkbox name="cruise_control" label="Control Crucero" :selected="old('cruise_control', $car->features->cruise_control)" />
                    <x-ui.checkbox name="bluetooth_connectivity" label="Bluetooth" :selected="old('bluetooth_connectivity', $car->features->bluetooth_connectivity)" />
                  </div>
                  <div class="col">
                    <x-ui.checkbox name="remote_start" label="Arranque Remoto" :selected="old('remote_start', $car->features->remote_start)" />
                    <x-ui.checkbox name="gps_navigation" label="GPS" :selected="old('gps_navigation', $car->features->gps_navigation)" />
                    <x-ui.checkbox name="heated_seats" label="Asientos Calefaccionados" :selected="old('heated_seats', $car->features->heated_seats)" />
                    <x-ui.checkbox name="climate_control" label="Control Climático" :selected="old('climate_control', $car->features->climate_control)" />
                    <x-ui.checkbox name="rear_parking_sensors" label="Sensores de Estacionamiento" :selected="old('rear_parking_sensors', $car->features->rear_parking_sensors)" />
                    <x-ui.checkbox name="leather_seats" label="Asientos de Cuero" :selected="old('leather_seats', $car->features->leather_seats)" />
                  </div>
                </div>
              </template>

              <template x-if="carTypeId && typeNames[carTypeId] === 'Motocicleta'">
                <div class="row">
                  <div class="col">
                    <x-ui.checkbox name="abs" label="ABS" :selected="old('abs', $car->features->abs)" />
                    <x-ui.checkbox name="bluetooth_connectivity" label="Bluetooth" :selected="old('bluetooth_connectivity', $car->features->bluetooth_connectivity)" />
                    <x-ui.checkbox name="gps_navigation" label="GPS" :selected="old('gps_navigation', $car->features->gps_navigation)" />
                  </div>
                  <div class="col">
                    <x-ui.checkbox name="heated_seats" label="Asientos Calefaccionados" :selected="old('heated_seats', $car->features->heated_seats)" />
                    <x-ui.checkbox name="leather_seats" label="Asientos de Cuero" :selected="old('leather_seats', $car->features->leather_seats)" />
                  </div>
                </div>
              </template>

              <template x-if="carTypeId && typeNames[carTypeId] === 'Maquinaria Pesada'">
                <div class="row">
                  <div class="col">
                    <x-ui.checkbox name="air_conditioning" label="Aire Acondicionado" :selected="old('air_conditioning', $car->features->air_conditioning)" />
                  </div>
                </div>
              </template>
            </div>

            <x-ui.form-group name="description" label="Descripción Detallada">
              <textarea name="description" rows="10">{{ old('description', $car->description) }}</textarea>
            </x-ui.form-group>

            <div class="form-group">
              <x-ui.checkbox name="published_at" label="Publicar inmediatamente" :selected="old('published_at', (bool)$car->published_at)" />
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
            <button class="btn btn-primary">Guardar Cambios</button>
          </div>
        </div>
      </form>
    </div>
  </main>
</x-app-layout>
