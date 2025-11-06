<!DOCTYPE html>
<html lang="es">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>Iseta Manager - Preceptor</title>

 <!-- Reutilizamos los estilos de Admin -->
 <link rel="stylesheet" href="{{ asset('css/Reset/reset.css') }}">
 <link rel="stylesheet" href="{{ asset('css/Admin/Edit/edit-page.css') }}">
 <link rel="stylesheet" href="{{ asset('css/Admin/main.css') }}">
 <link rel="stylesheet" href="{{ asset('css/Admin/aside.css') }}">
 <link rel="stylesheet" href="{{ asset('css/Admin/header-avatar.css') }}">
 <link rel="stylesheet" href="{{ asset('css/global.css') }}">
 <link rel="stylesheet" href="{{ asset('css/form.css') }}">

 <!-- Iconos -->
 <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</head>

<body>

 <!-- Scripts base -->
 <script src="{{ asset('js/libs/ElementEv.js') }}"></script>
 <script src="{{ asset('js/libs/ElementList.js') }}"></script>


 @include('Preceptor.mensaje')
 @include('Preceptor.aside') {{-- Aside específico para preceptor --}}
 @include('Componentes.confirmacion')

 <div class="admin-main">
  @yield('content')
 </div>

 <!-- Scripts funcionales -->
 <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
 <script src="{{ asset('js/confirmacion.js') }}"></script>
 <script src="{{ asset('js/filters.js') }}"></script>
 <script defer src="{{ asset('js/header-avatar.js') }}?v={{ time() }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
  crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

 @livewireScripts
</body>

</html>
