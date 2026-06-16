<x-error-layout title="Access Denied" bodyClass="error-page">
    <div class="error-content">
        <h1 class="error-title">403</h1>
        <h2 class="error-subtitle">Access Denied</h2>
        <p class="error-description">
            Sorry, you don't have permission to access this page.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</x-error-layout> 