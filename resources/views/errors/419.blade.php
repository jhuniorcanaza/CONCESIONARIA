<x-error-layout title="Page Expired" bodyClass="error-page">
    <div class="error-content">
        <h1 class="error-title">419</h1>
        <h2 class="error-subtitle">Page Expired</h2>
        <p class="error-description">
            Sorry, the page is expired. Please try again later or refresh the page.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</x-error-layout> 