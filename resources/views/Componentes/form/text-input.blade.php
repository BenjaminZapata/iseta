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

    {{-- LABEL SOLO CON TEXTO --}}
    <label for="{{ $name }}" class="label-input-y-75 @error($name) @enderror">
        {{ $label }}
    </label>

    {{-- INPUT + ICONO DENTRO --}}
    <div class="password-wrapper" style="position: relative; width: 75%;">

        <input
            value="{{ $default }}"
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            class="{{ $options['inputclass'] ?? '' }} @error($name) input-error @enderror"
            style="padding-right: 36px;"
            @foreach ($options as $attr => $val)
                @if ($attr !== 'inputclass' && $attr !== 'default')
                    {{ $attr }}="{{ $val }}"
                @endif
            @endforeach
        >

        @if($type === 'password')
            <span class="toggle-password"
                style="
                    position: absolute;
                    top: 50%;
                    right: 10px;
                    transform: translateY(-50%);
                    cursor: pointer;
                    color: #777;
                    font-size: 1.2rem;
                "
            >
                <i class="ti ti-eye"></i>
            </span>
        @endif
    </div>

    <div class="campo-alert">
        @error($name)
            {{ $message }}
        @enderror
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".password-wrapper").forEach(wrapper => {
        const input = wrapper.querySelector("input");
        const toggle = wrapper.querySelector(".toggle-password i");
        if (!input || !toggle) return;

        toggle.addEventListener("click", () => {
            const isPass = input.type === "password";
            input.type = isPass ? "text" : "password";
            toggle.classList.toggle("ti-eye");
            toggle.classList.toggle("ti-eye-off");
        });
    });
});
</script>
