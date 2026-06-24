@props(['title' => 'Admin Panel'])

<x-base-layout :$title bodyClass="admin-page">

    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="admin-logo">Admin Panel</a>
            </div>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
                <a href="{{ route('admin.moderation') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.moderation') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> Moderación
                </a>
                <a href="{{ route('admin.users') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Usuarios
                </a>
                <a href="{{ route('home') }}" class="admin-nav-item">
                    <i class="fas fa-arrow-left"></i> Volver al sitio
                </a>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-topbar">
                <h1>{{ $title }}</h1>
                <div class="admin-user-info">
                    {{ auth()->user()->name }}
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-logout">Salir</button>
                    </form>
                </div>
            </header>
            <div class="admin-main">
                {{ $slot }}
            </div>
        </main>
    </div>

</x-base-layout>
