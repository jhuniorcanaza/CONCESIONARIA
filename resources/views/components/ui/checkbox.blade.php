@props(['name', 'label', 'value' => 1, 'selected' => null])

<label {{ $attributes->merge(['class' => "checkbox"]) }}>
  <input
    type="checkbox"
    name="{{ $name }}"
    value="{{ $value }}"
    {{ $selected ? 'checked' : '' }}
  />
  {{ $label }}
</label>