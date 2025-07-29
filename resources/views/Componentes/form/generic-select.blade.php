@php
    $default = '';
    $id = '';

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

    if (isset($options['id'])) {
        $id = $options['id'];
    }

@endphp

<div class="{{$class}}">

    <label>{{$label}}</label>

    <select id="{{$id}}" name="{{$name}}" class="{{$options['inputclass']}}">
        @foreach ($optionsE as $key => $value)
            <option @selected($default == $key) value="{{$key}}">{{$value}}</option>
        @endforeach
    </select>

</div>