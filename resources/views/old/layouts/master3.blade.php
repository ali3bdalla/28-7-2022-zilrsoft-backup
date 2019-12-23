
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @yield('title')</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
<!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/skins/_all-skins.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://blackrockdigital.github.io/startbootstrap-sb-admin/css/sb-admin.css" rel="stylesheet">

    <style>

        i {
            margin: 7px !important
        }
        .option-buttons .button {
            margin: 5px;

        }
        .dropdown-menu
        {
            position: absolute !important;
            will-change: transform !important;
            top: 0px !important;
            left: 0px !important;
            transform: translate3d(-58px, 41px, 0px) !important;
            /*background: #01d1b2 !important; */
            color: white;
            z-index: 10000
        }
        a.dropdown-item, button.dropdown-item
        {
            padding: 10px;
            /* padding-right: 3rem; */
            text-align: center;
            /* white-space: nowrap; */
            width: 100%;
            /* padding: 6px; */
            border-bottom: 1px solid #eee;
            /*color: white*/
        }

        .table-container {
            overflow: inherit;
        }


        th {
            text-align: center !important;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

    @yield('page_css')

</head>
<body class="sidebar-mini skin-red-light">
<div class="wrapper" id="app">

    <header class="main-header ">
        @includeIf('layouts.header')
    </header>




    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('desctipion')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('management.dashboard')}}"><i class="fa fa-dashboard"></i> dashboard</a></li>
                <li><a href="@yield('route')"><i class="fa fa-info"></i>  @yield('title')</a></li>
            </ol>
        </section>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <aside class="main-sidebar" style="z-index:108;">
        @includeIf('layouts.sidebar')
    </aside>
    <footer class="main-footer">
        @includeIf('layouts.footer')



    </footer>

</div>


@yield('page_js')

</body>
</html>
