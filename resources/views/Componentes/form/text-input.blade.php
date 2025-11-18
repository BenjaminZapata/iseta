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

    {{-- LABEL --}}
    <label for="{{ $name }}" class="label-input-y-75 @error($name) @enderror">
        {{ $label }}
    </label>

    {{-- SI ES PASSWORD → WRAPPER --}}
    @if($type === 'password')
        <div class="password-wrapper">
            <input
                value="{{ $default }}"
                type="password"
                name="{{ $name }}"
                id="{{ $name }}"
                class="{{ $options['inputclass'] ?? '' }} @error($name) input-error @enderror"
                style="padding-right: 2.75rem;" {{-- espacio para el ícono --}}
                @foreach ($options as $attr => $val)
                    @if ($attr !== 'inputclass' && $attr !== 'default')
                        {{ $attr }}="{{ $val }}"
                    @endif
                @endforeach
            >

            <span class="toggle-password">
                <i class="ti ti-eye"></i>
            </span>
        </div>

    {{-- SI NO ES PASSWORD → INPUT NORMAL --}}
    @else
        <input
            value="{{ $default }}"
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            class="{{ $options['inputclass'] ?? '' }} @error($name) input-error @enderror"
            @foreach ($options as $attr => $val)
                @if ($attr !== 'inputclass' && $attr !== 'default')
                    {{ $attr }}="{{ $val }}"
                @endif
            @endforeach
        >
    @endif

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
        const toggle = wrapper.querySelector(".toggle-password");

        if (!input || !toggle) return;

        toggle.addEventListener("click", () => {
            const isPass = input.type === "password";
            input.type = isPass ? "text" : "password";

            const icon = toggle.querySelector("i");
            icon.classList.toggle("ti-eye", !isPass);
            icon.classList.toggle("ti-eye-off", isPass);
        });
    });
});
</script>

<style>
.password-wrapper {
    position: relative;
    width: 100%;
}

.password-wrapper input {
    width: 100%;
    padding-right: 2.75rem; /* espacio para el ícono */
}

.password-wrapper .toggle-password {
    position: absolute;
    top: 50%;
    right: 0.75rem;
    transform: translateY(-50%);
    cursor: pointer;
    color: #777;
    font-size: 1.2rem;
    pointer-events: auto;
}
</style>
