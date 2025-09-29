@php
    $default = '';

    if($item  && isset($item->$name)){
        if(isset($options['default'])){
            $default = old($name) ? old($name) : $options['default'];
        }else{
            $default = $item->$name;
        }
    }else{
        if(isset($options['default'])){
            $default = old($name) ? old($name) : $options['default'];
        }else{
            $default = old($name) ? old($name) : '';
        }
    }
@endphp

<div class="{{ $class }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <textarea 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="{{ $options['inputclass'] ?? '' }} @error($name) input-error @enderror" 
        rows="2"
        @foreach($options as $attr => $val)
            @if($attr !== 'inputclass' && $attr !== 'default')
                {{ $attr }}="{{ $val }}"
            @endif
        @endforeach
    >{{ $default }}</textarea>
    <div class="campo-alert">
        @error($name)
            {{ $message }}
        @enderror
    </div>
</div>