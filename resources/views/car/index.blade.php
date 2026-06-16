<x-app-layout title="Cars" bodyClass="page-my-cars">
<main>

    <div>
      <div class="container">
        <h1 class="car-details-page-title">My Cars</h1>
        <div class="card p-medium">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Date</th>
                  <th>Published</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($cars as $car)
                <tr>
                    <td>
                      <a href="{{ route('car.show', $car->id) }}">
                        <x-img
                          src="{{ $car->primaryImage->image_path }}"
                          alt=""
                          class="my-cars-img-thumbnail"
                        />
                      </a>
                    </td>
                    <td>
                      <a href="{{ route('car.show', $car->id) }}">
                        {{ $car->title }}
                      </a>
                    </td>
                    <td>{{ $car->getCreatedDate() }}</td>
                    <td>{{ $car->published_at && $car->published_at < now() ? "Yes" : "No"}}</td>
                    <td class="">
                      <a
                        href="{{ route('car.edit', $car->id) }}"
                        class="btn btn-edit inline-flex items-center"
                      >
                        <x-svg.edit /> Edit
                      </a>
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
                    </td>
                  </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center">No cars found, <a href="{{ route('car.create') }}"> create new car</a></td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{ $cars->links() }}

        </div>
      </div>
    </div>
  </main>
</x-app-layout>