<x-error-layout title="Server Error" bodyClass="error-page">
    <div class="error-content">
        <h1 class="error-title">500</h1>
        <h2 class="error-subtitle">Server Error</h2>
        <p class="error-description">
            An internal server error has occurred. Please try again later.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</x-error-layout> 