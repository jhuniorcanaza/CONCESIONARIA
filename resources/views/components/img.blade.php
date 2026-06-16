@props(['src', 'alt' => ''])

<img {{ $attributes->merge(['class' => "checkbox"]) }}
  src="{{ 
    filter_var($src, FILTER_VALIDATE_URL) 
    ? $src 
    : asset('storage/' . $src) }}" 
  alt="" 
/>
