@php
    $default = '';

    // Determinar el valor por defecto como string
    if ($item && isset($item->$name)) {
        $rawDefault = $options['default'] ?? $item->$name;
        $default = old($name, is_array($rawDefault) ? '' : $rawDefault);
    } else {
        $rawDefault = $options['default'] ?? '';
        $default = old($name, is_array($rawDefault) ? '' : $rawDefault);
    }

    // Asegurar que $optionsE sea iterable
    $optionsE = is_iterable($optionsE ?? null) ? $optionsE : [];

    // ID del campo
    $id = $options['id'] ?? '';
@endphp

<div class="{{ is_array($class) ? implode(' ', $class) : $class }}">

    <label>{{ $label }}</label>

    <select id="{{ $id }}"
            name="{{ $name }}"
            class="{{ $options['inputclass'] ?? '' }} @error($name) is-invalid @enderror">

        @foreach ($optionsE as $key => $value)
            <option @selected((string) $default === (string) $key)
                    value="{{ $key }}">
                {{ $value }}
            </option>
        @endforeach
    </select>

    {{-- 🔴 MOSTRAR ERROR DE VALIDACIÓN DEBAJO --}}
    @error($name)
        <p class="campo-alert">{{ $message }}</p>
    @enderror

</div>
