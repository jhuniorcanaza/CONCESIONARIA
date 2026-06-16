<x-error-layout title="Service Unavailable" bodyClass="error-page">
    <div class="error-content">
        <h1 class="error-title">503</h1>
        <h2 class="error-subtitle">Service Unavailable</h2>
        <p class="error-description">
            The server is currently unavailable or under maintenance. Please try again later.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</x-error-layout>
