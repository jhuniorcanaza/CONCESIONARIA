<x-app-layout title="Car Details">
    <main>
        <div class="container">
          <h1 class="car-details-page-title">{{ $car->title }}</h1>
          {{-- <div class="car-details-region">{{ $car->city->name }} - {{ $car->published_at->diffForHumans() }}</div> --}}
          <div class="car-details-region">{{ $car->city->name }} - {{ $car->published_at }}</div>
  
          <div class="car-details-content">
            <div class="car-images-and-description">
              <div class="car-images-carousel">
                <div class="car-image-wrapper">
                  <x-img
                    src="{{ $car->primaryImage->image_path }}"
                    alt=""
                    class="car-active-image"
                    id="activeImage"
                  />
                </div>

                @if($car->images->count() > 1)
                  <div class="car-image-thumbnails">
                      @foreach ($car->images as $image)
                          <x-img src="{{ $image->image_path }}" alt="" />
                      @endforeach
                  </div>

                  <button class="carousel-button prev-button" id="prevButton">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      style="width: 64px"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 19.5 8.25 12l7.5-7.5"
                      />
                    </svg>
                  </button>
                  <button class="carousel-button next-button" id="nextButton">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      style="width: 64px"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m8.25 4.5 7.5 7.5-7.5 7.5"
                      />
                    </svg>
                  </button>
                @endif

              </div>
  
              <div class="card car-detailed-description">
                <h2 class="car-details-title">Detailed Description</h2>
                {!! $car->description !!}
              </div>
  
              <div class="card car-detailed-description">
                <h2 class="car-details-title">Car Specifications</h2>
                <ul class="car-specifications">
                  <li>
                    <x-checkmark :status="$car->features->air_conditioning" />
                    Air Conditioning
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->power_windows" />
                    Power Windows
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->power_door_locks" />
                    Power Door Locks
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->cruise_control" />
                    Cruise Control
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->bluetooth_connectivity" />
                    Bluetooth Connectivity
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->remote_start" />
                    Remote Start
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->gps_navigation" />
                    GPS Navigation System
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->heated_seats" />
                    Heated Seats
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->climate_control" />
                    Climate Control
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->rear_parking_sensors" />
                    Rear Parking Sensors
                  </li>
                  <li>
                    <x-checkmark :status="$car->features->leather_seats" />
                    Leather Seats
                  </li>
                </ul>
              </div>
            </div>
            <div class="car-details card">
              <div class="flex items-center justify-between">
                <p class="car-details-price">${{ $car->price }}</p>
                <button class="btn-heart">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    style="width: 20px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                    />
                  </svg>
                </button>
              </div>
  
              <hr />
              <table class="car-details-table">
                <tbody>
                  <tr>
                    <th>Maker</th>
                    <td>{{ $car->maker->name }}</td>
                  </tr>
                  <tr>
                    <th>Model</th>
                    <td>{{ $car->model->name }}</td>
                  </tr>
                  <tr>
                    <th>Year</th>
                    <td>{{ $car->year }}</td>
                  </tr>
                  <tr>
                    <th>Mileage</th>
                    <td>{{ $car->mileage }} km</td>
                  </tr>
                  <tr>
                    <th>Vin</th>
                    <td>{{ $car->vin }}</td>
                  </tr>
                  <tr>
                    <th>Car Type</th>
                    <td>{{ $car->carType->name }}</td>
                  </tr>
                  <tr>
                    <th>Fuel Type</th>
                    <td>{{ $car->fuelType->name }}</td>
                  </tr>
                  <tr>
                    <th>Address</th>
                    <td>{{ $car->address }}</td>
                  </tr>
                </tbody>
              </table>
              <hr />
  
              <div class="flex gap-1 my-medium">
                <x-img src="{{ $car->user->avatar ? $car->user->avatar  : '/img/avatar.png' }}" alt="" class="car-details-owner-image"/>
                <div>
                  <h3 class="car-details-owner">{{ $car->user->name }}</h3>
                  <div class="text-muted">{{ $car->user->cars()->count() }} cars</div>
                </div>
              </div>
              <a href="{{ route('car.get-phone', $car) }}" id="carPhone" class="car-details-phone">
                <x-svg.phone />
                <span class="phone">
                  {{ \Illuminate\Support\Str::mask($car->phone, '*', -3) }}
                </span>
                <span class="car-details-phone-view">view full number</span>
              </a>

              <br />
              <hr />

              @if(auth()->user() && auth()->user()->can('update', $car))
                <a href="{{ route('car.edit', $car->id) }}"
                  class="btn btn-edit inline-flex items-center"
                >
                  <x-svg.edit /> Edit
                </a>
              @endif
              
              @if(auth()->user() && auth()->user()->can('delete', $car))
                <form 
                  action="{{ route('car.destroy', $car->id) }}"
                  method="POST"
                  class="inline-flex"
                  onsubmit="return confirm('Are you sure you want to delete this car?');"
                >
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete inline-flex items-center">
                    <x-svg.delete /> Delete
                  </button>
                </form>
              @endif

            </div>
          </div>
        </div>
      </main>
</x-app-layout>
