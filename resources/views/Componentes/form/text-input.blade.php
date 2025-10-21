@php
    $item = $item ?? null;

    if ($item && isset($item->$name)) {
        if (isset($options['default'])) {
            $default = old($name) ? old($name) : $options['default'];
        } else {
            $default = $item->$name;
        }
    } else {
        if (isset($options['default'])) {
            $default = old($name) ? old($name) : $options['default'];
        } else {
            $default = old($name) ? old($name) : '';
        }
    }
@endphp

<div class="{{ $class }}">
    <label for="{{ $name }}" class="label-input-y-75 @error($name) @enderror">
        {{ $label }}
        <input value="{{ $default }}" type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            class="{{ $options['inputclass'] ?? '' }} @error($name) input-error @enderror"
            @foreach ($options as $attr => $val)
                @if ($attr !== 'inputclass' && $attr !== 'default')
                    {{ $attr }}="{{ $val }}"
                @endif @endforeach>
    </label>

    <div class="campo-alert">
        @error($name)
            {{ $message }}
        @enderror
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input, textarea, select').forEach(function(el) {
            el.addEventListener('input', function() {
                if (el.classList.contains('input-error')) {
                    el.classList.remove('input-error');
                    let errorDiv = el.closest('div').querySelector('.campo-alert');
                    if (errorDiv) {
                        errorDiv.innerHTML = ''; // limpia el mensaje
                    }
                }
            });
        });
    });
</script>
