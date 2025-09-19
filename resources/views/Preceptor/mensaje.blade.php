{{-- Mensajes agrupados por tipo --}}
@if (Session::get('mensajes'))
    <div class="alert-container">
        @foreach (Session::get('mensajes') as $tipo => $lista)
            @foreach ($lista as $msj)
                <div @class([
                    'alert',
                    'alert-mensaje' => $tipo === 'mensaje',
                    'alert-aviso' => $tipo === 'aviso',
                    'alert-error' => $tipo === 'error',
                ])>
                    <span class="icon">
                        @if ($tipo === 'error')
                            <i class="ti ti-circle-x-filled"></i>
                        @elseif ($tipo === 'aviso')
                            <i class="ti ti-alert-circle-filled"></i>
                        @else
                            <i class="ti ti-circle-check-filled"></i>
                        @endif
                    </span>
                    <span class="message">{{ $msj }}</span>
                    <span class="close pointer">&times;</span>
                </div>
            @endforeach
        @endforeach
    </div>
@endif

{{-- Mensaje individual --}}
@if (Session::get('mensaje'))
    <div class="alert-container">
        @if (is_array(Session::get('mensaje')))
            @foreach (Session::get('mensaje') as $msj)
                <div class="alert alert-mensaje">
                    <span class="message">{{ $msj }}</span>
                    <span class="close pointer">&times;</span>
                </div>
            @endforeach
        @else
            <div class="alert alert-mensaje">
                <i class="ti ti-circle-check-filled"></i>
                <span class="message">{{ Session::get('mensaje') }}</span>
                <span class="close pointer">&times;</span>
            </div>
        @endif
    </div>
@endif

{{-- Aviso individual --}}
@if (Session::get('aviso'))
    <div class="alert-container">
        @if (is_array(Session::get('aviso')))
            @foreach (Session::get('aviso') as $msj)
                <div class="alert alert-aviso">
                    <span class="message">{{ $msj }}</span>
                    <span class="close pointer">&times;</span>
                </div>
            @endforeach
        @else
            <div class="alert alert-aviso">
                <i class="ti ti-alert-circle-filled"></i>
                <span class="message">{{ Session::get('aviso') }}</span>
                <span class="close pointer">&times;</span>
            </div>
        @endif
    </div>
@endif

{{-- Error individual --}}
@if (Session::get('error'))
    <div class="alert-container">
        @if (is_array(Session::get('error')))
            @foreach (Session::get('error') as $msj)
                <div class="alert alert-error">
                    <span class="message">{{ $msj }}</span>
                    <span class="close pointer">&times;</span>
                </div>
            @endforeach
        @else
            <div class="alert alert-error">
                <i class="ti ti-circle-x-filled"></i>
                <span class="message">{!! Session::get('error') !!}</span>
                <span class="close pointer">&times;</span>
            </div>
        @endif
    </div>
@endif

{{-- Errores de validación --}}
@if ($errors->any())
    <div class="alert-container">
        <div class="alert alert-error">
            <div class="message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            <span class="close pointer">&times;</span>
        </div>
    </div>
@endif
