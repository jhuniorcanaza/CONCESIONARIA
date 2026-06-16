@props(['name' => 'button', 'image' => '', 'url' => '#'])

<a href="{{ $url }}"  {{ $attributes->merge(['class' => 'btn btn-default flex justify-center items-center gap-1']) }}>
    @if($image)
        <img src="{{ $image }}" alt="" style="width: 20px" />
    @endif
    {{ $name }}
</a>
