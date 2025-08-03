@php
    $default = '';
    $id = $options['id'] ?? '';

    // Determinar el valor por defecto
    if ($item && isset($item->$name)) {
        $default = old($name, $options['default'] ?? $item->$name);
    } else {
        $default = old($name, $options['default'] ?? '');
    }

    // Asegurar que $optionsE sea iterable
    $optionsE = is_iterable($optionsE ?? null) ? $optionsE : [];
@endphp

<div class="{{ $class }}">
    <label>{{ $label }}</label>

    <select id="{{ $id }}" name="{{ $name }}" class="{{ $options['inputclass'] ?? '' }}">
        @foreach ($optionsE as $key => $value)
            <option @selected($default == $key) value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>