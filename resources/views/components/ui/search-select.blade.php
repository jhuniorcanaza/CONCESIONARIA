@props(['elements', 'parent' => '', 'title' => '', 'filtered' => null])

<select {{ $attributes->merge(['class' => ""]) }}>
    
    @if(!empty($title))
        <option value="">{{ $title }}</option>
    @endif

    @foreach ($elements as $element)
        <option 
            value="{{ $element->id }}"
            @if($filtered && $filtered == $element->id) selected @endif
            @if($parent) data-parent="{{ $element->{$parent} }}" style="display: none" @endif
        >{{ $element->name }}</option>        
    @endforeach
</select>