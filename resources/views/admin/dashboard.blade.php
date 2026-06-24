<x-layouts.admin title="Dashboard">

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-car"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $totalCars }}</span>
                <span class="stat-label">Total Anuncios</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $pendingCars }}</span>
                <span class="stat-label">Pendientes</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $totalUsers }}</span>
                <span class="stat-label">Usuarios Registrados</span>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <h2 class="card-title">Anuncios por Categoría</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carsByType as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td><strong>{{ $type->cars_count }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.admin>
