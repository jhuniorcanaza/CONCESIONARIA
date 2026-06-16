<x-error-layout title="Page Not Found" bodyClass="error-page">
    <div class="error-content">
        <h1 class="error-title">404</h1>
        <h2 class="error-subtitle">Page Not Found</h2>
        <p class="error-description">
            Sorry, the page you are looking for doesn't exist or has been moved.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</x-error-layout> 