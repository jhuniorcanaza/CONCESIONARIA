@props(['name', 'label' => null, 'form' => null])

@php
    $errorClass = $errors->has($name) ? 'has-error' : '';
@endphp

<div {{ $attributes->merge(['class' => "form-group $errorClass"]) }}>

    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    {{ $slot }}    

    @error($name, $form)
      <div class="error-message">{{ $message }}</div>
    @enderror
</div>