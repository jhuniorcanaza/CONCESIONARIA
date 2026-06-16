@props(['color', 'bgColor' => 'white'])

<div class="card" style="color: {{ $color }}; background-color: {{ $bgColor }}">
    <div class="card-header">{{ $header }}</div>
    {{ $slot }}
    <div class="card-footer">{{ $footer }}</div>
</div>