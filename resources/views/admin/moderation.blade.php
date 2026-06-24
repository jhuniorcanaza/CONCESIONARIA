<x-layouts.admin title="Moderación de Anuncios">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="pendingCarsTable">
                @forelse($pendingCars as $car)
                    <tr id="car-row-{{ $car->id }}">
                        <td>
                            <img src="/storage/{{ $car->primaryImage->image_path }}"
                                 alt="" class="admin-thumb" onerror="this.src='/img/car-default.jpg'">
                        </td>
                        <td>
                            <a href="{{ route('car.show', $car) }}" target="_blank" class="admin-link">
                                {{ $car->maker->name }} {{ $car->model->name }} - {{ $car->year }}
                            </a>
                        </td>
                        <td>{{ $car->carType->name }}</td>
                        <td>{{ $car->user->name }}</td>
                        <td>{{ $car->created_at->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-approve"
                                    data-car-id="{{ $car->id }}"
                                    onclick="approveCar({{ $car->id }})">
                                <i class="fas fa-check"></i> Aprobar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay anuncios pendientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $pendingCars->links() }}
    </div>

</x-layouts.admin>

<script>
function approveCar(carId) {
    const btn = document.querySelector(`[data-car-id="${carId}"]`);
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`{{ url('admin/approve') }}/${carId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById(`car-row-${carId}`);
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(() => row.remove(), 300);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Aprobar';
        alert('Error al aprobar el anuncio.');
    });
}
</script>
