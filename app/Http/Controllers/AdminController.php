<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarType;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalCars = Car::count();
        $pendingCars = Car::where('is_approved', false)->count();
        $totalUsers = User::count();
        $carsByType = CarType::withCount('cars')->get();

        return view('admin.dashboard', compact(
            'totalCars', 'pendingCars', 'totalUsers', 'carsByType'
        ));
    }

    public function moderation()
    {
        $pendingCars = Car::where('is_approved', false)
            ->with(['maker', 'model', 'carType', 'user', 'primaryImage'])
            ->latest()
            ->paginate(15);

        return view('admin.moderation', compact('pendingCars'));
    }

    public function approve(Car $car)
    {
        $car->update(['is_approved' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Anuncio aprobado correctamente.'
        ]);
    }

    public function users()
    {
        $users = User::withCount('cars')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateRole(User $user)
    {
        $newRole = $user->role === 'admin' ? 'client' : 'admin';
        $user->update(['role' => $newRole]);

        return response()->json([
            'success' => true,
            'message' => "Rol cambiado a {$newRole} correctamente.",
            'role' => $newRole
        ]);
    }

    public function toggleActive(User $user)
    {
        if ($user->is(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes desactivar tu propia cuenta.'
            ], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activada' : 'desactivada';

        return response()->json([
            'success' => true,
            'message' => "Cuenta {$status} correctamente.",
            'is_active' => $user->is_active
        ]);
    }
}
