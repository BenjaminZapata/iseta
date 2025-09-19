
@php
    $item = $item ?? null;

    if($item  && isset($item->$name)){
        if(isset($options['default'])){
            $default = old($name)?old($name):$options['default'];
        }else{
            $default = $item->$name;
        }
    }else{
        if(isset($options['default'])){
            $default = old($name)?old($name):$options['default'];
        }else{
            $default = old($name)?old($name):'';
        }
    }
@endphp

<div class="{{$class}}">

    <label>{{$label}}</label>
    {{-- @dd($item->$name); --}}
    @if ($item)
        <input value="{{$default}}" type="{{$type}}" name="{{$name}}" class="{{$options['inputclass']}}">
    @else
        <input value="{{$default}}" type="{{$type}}" name="{{$name}}" class="{{$options['inputclass']}}">
    @endif

</div>
