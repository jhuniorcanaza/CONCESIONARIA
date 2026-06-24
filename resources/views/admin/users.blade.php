<x-layouts.admin title="Gestión de Usuarios">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Anuncios</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td>
                            <div class="flex items-center gap-1">
                                <img src="{{ $user->avatar ? '/storage/' . $user->avatar : '/img/avatar.png' }}"
                                     alt="" class="admin-avatar"
                                     onerror="this.src='/img/avatar.png'">
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-client' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>{{ $user->cars_count }}</td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <button class="btn btn-sm {{ $user->role === 'admin' ? 'btn-outline' : 'btn-primary' }}"
                                    onclick="changeRole({{ $user->id }})">
                                {{ $user->role === 'admin' ? 'Hacer Client' : 'Hacer Admin' }}
                            </button>

                            @if(!$user->is(auth()->user()))
                                <button class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                                        onclick="toggleActive({{ $user->id }})">
                                    {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            @else
                                <span class="text-muted text-small">Eres tú</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $users->links() }}
    </div>

</x-layouts.admin>

<script>
function changeRole(userId) {
    if (!confirm('¿Cambiar el rol de este usuario?')) return;

    fetch(`{{ url('admin/users') }}/${userId}/role`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error al cambiar el rol.');
        }
    })
    .catch(() => alert('Error de conexión.'));
}

function toggleActive(userId) {
    if (!confirm('¿Cambiar el estado de esta cuenta?')) return;

    fetch(`{{ url('admin/users') }}/${userId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error al cambiar el estado.');
        }
    })
    .catch(() => alert('Error de conexión.'));
}
</script>
