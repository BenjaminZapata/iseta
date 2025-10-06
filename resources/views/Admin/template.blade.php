<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iseta manager</title>


    <link rel="stylesheet" href="{{ asset('css/Reset/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/Edit/edit-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/aside.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/header-avatar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/create-carrera.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/edit-carrera.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/correlativa.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</head>

<body>
    @livewireStyles
    @livewireScripts
    <script src="{{ asset('js/libs/ElementEv.js') }}"></script>
    <script src="{{ asset('js/libs/ElementList.js') }}"></script>

    @include('Componentes.mensaje')
    @include('Componentes.aside')
    @include('Componentes.confirmacion')


    <div class="admin-main">
        @yield('content')
    </div>



    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/confirmacion.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script defer src="{{ asset('js/header-avatar.js') }}?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>


</body>

</html>
