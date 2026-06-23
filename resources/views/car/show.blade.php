<x-app-layout title="Car Details">
    <main>
        <div class="container">
          <h1 class="car-details-page-title">{{ $car->title }}</h1>
          <div class="car-details-region">{{ $car->city->name }} - {{ $car->published_at }}</div>
          @if(!$car->is_approved)
            <div class="alert alert-warning">Este anuncio está pendiente de aprobación.</div>
          @endif

          <div class="car-details-content">
            <div class="car-images-and-description">
              {{-- Carrusel de imágenes --}}
              <div class="car-images-carousel">
                <div class="car-image-wrapper">
                  <x-img src="{{ $car->primaryImage->image_path }}" alt="" class="car-active-image" id="activeImage" />
                </div>

                @if($car->images->count() > 1)
                  <div class="car-image-thumbnails">
                      @foreach ($car->images as $image)
                          <x-img src="{{ $image->image_path }}" alt="" />
                      @endforeach
                  </div>
                  <button class="carousel-button prev-button" id="prevButton"> ... </button>
                  <button class="carousel-button next-button" id="nextButton"> ... </button>
                @endif
              </div>

              {{-- Descripción detallada --}}
              <div class="card car-detailed-description">
                <h2 class="car-details-title">Detailed Description</h2>
                {!! $car->description !!}
              </div>

              {{-- Especificaciones (features) --}}
              <div class="card car-detailed-description">
                <h2 class="car-details-title">Características</h2>
                <ul class="car-specifications">
                  @if($car->carType->name === 'Maquinaria Pesada')
                    <li><x-checkmark :status="$car->features->air_conditioning" /> Aire Acondicionado</li>
                  @elseif($car->carType->name === 'Motocicleta')
                    <li><x-checkmark :status="$car->features->abs" /> ABS</li>
                    <li><x-checkmark :status="$car->features->bluetooth_connectivity" /> Bluetooth</li>
                    <li><x-checkmark :status="$car->features->gps_navigation" /> GPS</li>
                    <li><x-checkmark :status="$car->features->heated_seats" /> Asientos Calefaccionados</li>
                    <li><x-checkmark :status="$car->features->leather_seats" /> Asientos de Cuero</li>
                  @else
                    <li><x-checkmark :status="$car->features->air_conditioning" /> Aire Acondicionado</li>
                    <li><x-checkmark :status="$car->features->abs" /> ABS</li>
                    <li><x-checkmark :status="$car->features->power_windows" /> Vidrios Eléctricos</li>
                    <li><x-checkmark :status="$car->features->power_door_locks" /> Seguros Eléctricos</li>
                    <li><x-checkmark :status="$car->features->cruise_control" /> Control Crucero</li>
                    <li><x-checkmark :status="$car->features->bluetooth_connectivity" /> Bluetooth</li>
                    <li><x-checkmark :status="$car->features->remote_start" /> Arranque Remoto</li>
                    <li><x-checkmark :status="$car->features->gps_navigation" /> GPS</li>
                    <li><x-checkmark :status="$car->features->heated_seats" /> Asientos Calefaccionados</li>
                    <li><x-checkmark :status="$car->features->climate_control" /> Control Climático</li>
                    <li><x-checkmark :status="$car->features->rear_parking_sensors" /> Sensores de Estacionamiento</li>
                    <li><x-checkmark :status="$car->features->leather_seats" /> Asientos de Cuero</li>
                  @endif
                </ul>
              </div>
            </div>

            {{-- Sidebar de detalles --}}
            <div class="car-details card">
              <div class="flex items-center justify-between">
                <p class="car-details-price">${{ $car->price }}</p>
                <button class="btn-heart"> ... </button>
              </div>

              <hr />
              <table class="car-details-table">
                <tbody>
                  <tr><th>Maker</th><td>{{ $car->maker->name }}</td></tr>
                  <tr><th>Model</th><td>{{ $car->model->name }}</td></tr>
                  <tr><th>Year</th><td>{{ $car->year }}</td></tr>
                  <tr>
                    <th>
                      @if($car->carType->name === 'Maquinaria Pesada') Horas de uso
                      @else Kilometraje
                      @endif
                    </th>
                    <td>
                      @if($car->carType->name === 'Maquinaria Pesada')
                        {{ $car->mileage }} hrs
                      @else
                        {{ $car->mileage }} km
                      @endif
                    </td>
                  </tr>
                  <tr><th>Vin</th><td>{{ $car->vin }}</td></tr>
                  <tr><th>Car Type</th><td>{{ $car->carType->name }}</td></tr>
                  <tr><th>Fuel Type</th><td>{{ $car->fuelType->name }}</td></tr>

                  {{-- Campos dinámicos según tipo --}}
                  @if($car->carType->name === 'Auto')
                    @if($car->body_type)
                      <tr><th>Carrocería</th><td>{{ $car->body_type }}</td></tr>
                    @endif
                    @if($car->drive_type)
                      <tr><th>Tracción</th><td>{{ $car->drive_type }}</td></tr>
                    @endif
                  @endif

                  @if($car->carType->name === 'Motocicleta')
                    @if($car->engine_cc)
                      <tr><th>Cilindrada</th><td>{{ $car->engine_cc }} cc</td></tr>
                    @endif
                    @if($car->bike_type)
                      <tr><th>Tipo de Moto</th><td>{{ $car->bike_type }}</td></tr>
                    @endif
                    @if($car->start_type)
                      <tr><th>Arranque</th><td>{{ $car->start_type }}</td></tr>
                    @endif
                  @endif

                  @if($car->carType->name === 'Maquinaria Pesada')
                    @if($car->road_type)
                      <tr><th>Rodado</th><td>{{ $car->road_type }}</td></tr>
                    @endif
                    @if($car->machinery_type)
                      <tr><th>Maquinaria</th><td>{{ $car->machinery_type }}</td></tr>
                    @endif
                  @endif

                  <tr><th>Address</th><td>{{ $car->address }}</td></tr>
                </tbody>
              </table>
              <hr />

              {{-- Dueño --}}
              <div class="flex gap-1 my-medium">
                <x-img src="{{ $car->user->avatar ? $car->user->avatar : '/img/avatar.png' }}" alt="" class="car-details-owner-image"/>
                <div>
                  <h3 class="car-details-owner">{{ $car->user->name }}</h3>
                  <div class="text-muted">{{ $car->user->cars()->count() }} cars</div>
                </div>
              </div>

              {{-- Teléfono --}}
              <a href="{{ route('car.get-phone', $car) }}" id="carPhone" class="car-details-phone">
                <x-svg.phone />
                <span class="phone">{{ \Illuminate\Support\Str::mask($car->phone, '*', -3) }}</span>
                <span class="car-details-phone-view">view full number</span>
              </a>

              {{-- WhatsApp --}}
              @if($car->phone)
                <a href="https://wa.me/591{{ preg_replace('/\D/', '', $car->phone) }}?text=Hola,%20estoy%20interesado%20en%20el%20vehículo%20{{ rawurlencode($car->title) }}%20que%20vi%20en%20ruedas.store." 
                   target="_blank" 
                   class="btn-whatsapp flex items-center justify-center gap-1 my-medium" 
                   style="background-color: #25D366; color: white; padding: 10px; border-radius: 5px; text-decoration: none; font-weight: bold; text-align: center;">
                   <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" style="display: inline-block; vertical-align: middle;">
                       <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.96 9.96 0 001.37 5.05L2 22l5.145-1.348a9.92 9.92 0 004.858 1.258h.004c5.507 0 9.99-4.478 9.99-9.984a9.96 9.96 0 00-2.925-7.063A9.91 9.91 0 0012.012 2zm5.794 14.156c-.249.703-1.442 1.373-1.986 1.458-.49.076-1.127.14-3.277-.75-2.748-1.137-4.512-3.93-4.65-4.113-.136-.183-1.109-1.472-1.109-2.807 0-1.336.702-1.993.95-2.259.248-.266.545-.333.727-.333h.519c.165 0 .386-.062.595.44.209.503.719 1.75.782 1.879.062.128.104.278.018.448-.086.17-1.282.898-1.282.898s-.17.114-.07.288c.097.172.434.717.93 1.16.638.57 1.173.748 1.338.834.165.085.259.07.356-.04.097-.11.413-.48.523-.647.109-.168.22-.14.372-.084.152.056.966.455 1.132.538.165.084.276.126.317.197.042.072.042.413-.207 1.117z"/>
                   </svg>
                   Contactar por WhatsApp
                </a>
              @endif

              <br /><hr />

              @can('update', $car)
                <a href="{{ route('car.edit', $car->id) }}" class="btn btn-edit inline-flex items-center">
                  <x-svg.edit /> Edit
                </a>
              @endcan

              @can('delete', $car)
                <form action="{{ route('car.destroy', $car->id) }}" method="POST" class="inline-flex"
                      onsubmit="return confirm('Are you sure you want to delete this car?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-delete inline-flex items-center">
                    <x-svg.delete /> Delete
                  </button>
                </form>
              @endcan
            </div>
          </div>
        </div>
      </main>
</x-app-layout>
