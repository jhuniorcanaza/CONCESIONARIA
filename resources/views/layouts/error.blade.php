@props(['title' => '', 'bodyClass' => ''])

<x-base-layout :$title :$bodyClass>
    <main class="error-page">
        <div class="container">
            {{ $slot }}
        </div>
    </main>
</x-base-layout> 