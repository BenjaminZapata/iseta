@if (Session::get('mensajes'))


<div class="z-index-top flex.col right-3 bottom-3 fixed">
  @foreach (Session::get('mensajes') as $tipo => $lista)
  {{-- <div class="z-index-top flex.col right-3 bottom-3 fixed"> --}}
        @foreach ($lista as $msj)
          <div @class([
            'up-not_green' => $tipo=='mensaje',
            'up-not_yellow' => $tipo=='aviso',
            'up-not_red' => $tipo=='error',
            'toast px-3 py-2 gap-2 my-1 flex items-center rounded shadow-md' => true])>
            @if ($tipo=='error')
            <i class="ti ti-circle-x-filled"></i>
          @elseif ($tipo=='aviso')
            <i class="ti ti-alert-circle-filled"></i>
          @else
            <i class="ti ti-circle-check-filled"></i>
          @endif
            <span class="w-100p">{{$msj}}</span>
            <span class="close pointer">&times;</span>
          </div>
        @endforeach
  {{-- </div> --}}
  @endforeach
  </div>
@endif


@if (Session::get('mensaje'))
  <div class="z-index-top flex.col right-3 bottom-3 fixed">
    @if (is_array(Session::get('mensaje')))
        @foreach (Session::get('mensaje') as $msj)
          <div class="toast px-3 py-2 gap-2 my-1 flex just-between items-center rounded shadow-md up-not_green">
            <span class="">{{$msj}}</span>
            <span class="close pointer">&times;</span>
          </div>
        @endforeach
    @else
      <div class="toast px-3 py-2 gap-2 my-1 flex just-between items-center rounded shadow-md up-not_green">
        <i class="ti ti-circle-check-filled"></i>
        <p class="">{{ Session::get('mensaje') }}</p>
        <span class="close pointer">&times;</span>
      </div>
    @endif
  </div>
@endif

@if (Session::get('aviso'))
  <div class="z-index-top flex.col right-3 bottom-3 fixed">
    @if (is_array(Session::get('aviso')))
        @foreach (Session::get('aviso') as $msj)
          <div class="toast px-3 py-2 gap-2 my-1 flex just-between items-center rounded shadow-md up-not_yellow">
            <span class="">{{$msj}}</span>
            <span class="close pointer">&times;</span>
          </div>
        @endforeach
    @else
      <div class="toast px-3 py-2 gap-2 my-1 flex just-between items-center rounded shadow-md up-not_yellow">
        <i class="ti ti-alert-circle-filled"></i>
        <p class="">{{ Session::get('aviso') }}</p>
        <span class="close pointer">&times;</span>
      </div>
    @endif
  </div>
@endif

@if (Session::get('error'))
  <div class="z-index-top flex.col right-3 bottom-3 fixed">
    @if (is_array(Session::get('error')))
        @foreach (Session::get('error') as $msj)
          <div class=" toast px-3 py-2 gap-2 my-1 flex just-between items-center rounded shadow-md up-not_red">
            <span class="">{{$msj}}</span>
            <span class="close pointer">&times;</span>
          </div>
        @endforeach
    @else
      <div class="toast px-3 py-2 gap-2 my-1 flex just-between items-center rounded shadow-md up-not_red">
              <i class="ti ti-circle-x-filled"></i>
              <p class="">{!! Session::get('error') !!}</p>
              <span class="close pointer">&times;</span>
      </div>
    @endif
  </div>
@endif


@if ($errors->any())
  <div class="z-index-top toast px-2 py-1 gap-2 fixed flex rounded right-3 bottom-3 shadow-md up-not_red">
    <div class="flex-col">
    @foreach ($errors->all() as $error)
      <p class="">{{$error}}</p>
    @endforeach
  </div>
    <span class="pointer close">&times;</span>
  </div>

@endif
