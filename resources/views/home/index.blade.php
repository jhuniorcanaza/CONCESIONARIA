<x-app-layout title="Inicio">

    <!-- Slider Principal -->
    <section class="hero-slider">
        <div class="hero-slides">
            @if ($carouselCars->isNotEmpty())
                @foreach ($carouselCars as $car)
                    <div class="hero-slide">

                        {{-- Imagen como fondo completo --}}
                        <div class="hero-slide-image">
                            <x-img
                                src="{{ $car->primaryImage->image_path }}"
                                alt="{{ $car->maker->name }} {{ $car->model->name }}"
                                class="img-responsive"
                            />
                        </div>

                        {{-- Overlay oscuro --}}
                        <div class="hero-slide-overlay"></div>

                        {{-- Contenido centrado --}}
                        <div class="container hero-slide-inner">
                            <div class="hero-slide-copy">
                                
                                <h2 class="hero-slide-title">{{ $car->year }} {{ Str::upper($car->maker->name) }} {{ Str::upper($car->model->name) }}</h2>
                                <p class="hero-slide-subtitle">{{ \Illuminate\Support\Str::limit($car->description ?? 'Sin descripción disponible', 100) }}</p>

                                <div class="hero-slide-price-section">
                                    <p class="hero-slide-price">${{ number_format($car->price, 0, '.', ',') }}</p>
                                </div>

                                <div class="hero-slide-actions">
                                    <button onclick="location.href='{{ route('car.show', $car) }}'" class="btn btn-hero-primary">
                                        Descubrir modelo
                                    </button>
                                    <button onclick="location.href='{{ route('car.search') }}'" class="btn btn-hero-secondary">
                                        Ver catálogo
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            @else
                <div class="hero-slide">
                    <div class="hero-slide-overlay" style="background:#111;opacity:1;"></div>
                    <div class="container hero-slide-inner">
                        <div class="hero-slide-copy">
                            <span class="hero-label">Próximamente</span>
                            <h2 class="hero-slide-title">Nuevos ingresos en camino</h2>
                            <p class="hero-slide-subtitle">Activa la opción en tu catálogo para mostrar autos en portada.</p>
                            <div class="hero-slide-actions">
                                <button onclick="location.href='{{ route('car.search') }}'" class="btn btn-hero-primary">Buscar autos</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Indicadores --}}
            <div class="hero-slide-indicators">
                @if ($carouselCars->isNotEmpty())
                    @foreach ($carouselCars as $index => $car)
                        <div class="indicator-dot {{ $index === 0 ? 'active' : '' }}"></div>
                    @endforeach
                @else
                    <div class="indicator-dot active"></div>
                @endif
            </div>

            {{-- Flechas --}}
            <button type="button" class="hero-slide-prev">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button type="button" class="hero-slide-next">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </section>
    <!--/ Slider Principal -->

    <main>

        <!-- Buscador -->
        <section class="find-a-car">
            <div class="container">
                <x-search-form />
            </div>
        </section>

        <!-- Autos Destacados -->
        <section>
            <div class="container">
                    <div class="section-heading">
                        <h2>Autos Destacados</h2>
                    </div>
                    <div class="car-items-listing featured-listing" id="featured-listing">
                        @forelse ($featuredCars as $index => $car)
                            <div class="featured-item {{ $index >= 5 ? 'featured-hidden' : '' }}" data-index="{{ $index }}">
                                <x-car-item :$car />
                            </div>
                        @empty
                            <p style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #999;">
                                No hay autos destacados disponibles en este momento.
                            </p>
                        @endforelse
                    </div>

                    @if ($featuredCars->count() > 5)
                        <div class="featured-controls">
                            <button id="featured-show-more" class="btn btn-hero-primary">Ver más</button>
                        </div>
                    @endif
                </div>
        </section>

        <!-- Últimos Añadidos -->
        <section>
            <div class="container">
                <div class="section-heading">
                    <h2>Últimos Añadidos</h2>
                </div>
                <div class="car-items-listing">
                    @foreach ($cars as $car)
                        <x-car-item :$car />
                    @endforeach
                </div>

                {{ $cars->links() }}
            </div>
        </section>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const btn = document.getElementById('featured-show-more');
        const container = document.getElementById('featured-listing');
        if (!btn || !container) return;

        // estado inicial: 'more' permite revelar en incrementos, 'less' colapsa
        btn.dataset.state = 'more';

        btn.addEventListener('click', function(){
            const hidden = Array.from(document.querySelectorAll('.featured-listing .featured-hidden'));

            if (btn.dataset.state === 'less') {
                // colapsar a 5 primeros
                const items = Array.from(document.querySelectorAll('.featured-listing .featured-item'));
                items.forEach((el, idx) => {
                    if (idx >= 5) el.classList.add('featured-hidden');
                });
                btn.textContent = 'Ver más';
                btn.dataset.state = 'more';
                // desplazar al inicio de la sección
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            // modo 'more': revelar siguientes 5
            if (hidden.length === 0) {
                // si ya no hay ocultos, cambiar a 'less'
                btn.textContent = 'Ver menos';
                btn.dataset.state = 'less';
                return;
            }

            const next = hidden.slice(0, 5);
            next.forEach(el => el.classList.remove('featured-hidden'));

            // si ya no quedan ocultos, cambiar botón a 'Ver menos'
            if (document.querySelectorAll('.featured-listing .featured-hidden').length === 0) {
                btn.textContent = 'Ver menos';
                btn.dataset.state = 'less';
            } else {
                btn.textContent = 'Ver más';
                btn.dataset.state = 'more';
            }

            // desplazar suavemente al primer item mostrado
            if (next.length) {
                next[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
    </script>

    </x-app-layout>