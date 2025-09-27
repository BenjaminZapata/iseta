<div class="{{ $class }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="file"
           name="{{ $name }}"
           id="{{ $name }}"
           class="{{ $options['inputclass'] ?? 'form-control' }}"
           accept="application/pdf">
</div>
