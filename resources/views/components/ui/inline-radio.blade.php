@props(['elements', 'name', 'selected' => null])

<div {{ $attributes->merge(['class' => "row"]) }}>
  @foreach ($elements as $element)
    <div class="col">
      <label class="inline-radio">
        <input type="radio" name="{{ $name }}" value="{{ $element->id }}" {{ $element->id == $selected ? 'checked' : '' }}/>
        {{ $element->name }}
      </label>
    </div>
  @endforeach
</div>