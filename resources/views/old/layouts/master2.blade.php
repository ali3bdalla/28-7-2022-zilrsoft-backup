<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @yield('title')</title>
    @yield('translator')


    <script defer>
        window.config = `@json($organization_config)`

    </script>

    <script defer>
        window.reusable_translator = `@json(trans('reusable'))`;
        window.messages = `@json(trans('messages'))`
    </script>


    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
<!-- <link href="{{ asset('head') }}" rel="stylesheet"> -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">

    <link rel="stylesheet"
          href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/skins/_all-skins.min.css">


    {{--    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">--}}
    {{--    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/jvectormap/jquery-jvectormap.css">--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    {{----}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style>

        i {
            margin: 7px !important
        }

        .option-buttons .button {
            margin: 5px;

        }

        .dropdown-menu {
            position: absolute !important;
            will-change: transform !important;
            top: 0px !important;
            left: 0px !important;
            transform: translate3d(-58px, 41px, 0px) !important;
            /*background: #01d1b2 !important; */
            color: white;
            z-index: 10000
        }

        a.dropdown-item, button.dropdown-item {
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

        input {
            text-align: center !important;
        }


        button > svg {
            margin: 4px !important;
        }

        .datedirection {
            direction: ltr !important;
        }
    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

    @if(app()->isLocale('ar'))
        <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{asset('css/rlt/custom.css')}}">

    @endif
    {{--    <link href="https://blackrockdigital.github.io/startbootstrap-sb-admin/css/sb-admin.css" rel="stylesheet">--}}


    {{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />--}}

    @yield('page_css')

</head>
<body class="sidebar-mini skin-green sidebar-collapse" @keydown.esc="console.log('23')">
{{--<body class="sidebar-mini skin-green">--}}
<div class="wrapper" id="app">

    <header class="main-header ">
        @includeIf('layouts.header')
    </header>


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('description')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('management.dashboard')}}">لوحة التحكم</a></li>
                {{--                <li><a href="@yield('route')">  @yield('title')</a></li>--}}
            </ol>
        </section>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <aside class="main-sidebar" style="z-index:108;padding-top: 0px !important;">
        @includeIf('layouts.sidebar')
    </aside>
    <footer class="main-footer">
        @includeIf('layouts.footer')


    </footer>

</div>


<script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/dist/js/adminlte.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js" defer></script>
{{--<script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>--}}
<script src="{{ asset('js/main.js') }}" defer></script>
<style scoped>
    .select2-container {
        width: 100% !important;
    }
</style>
@yield('page_js')

</body>
</html>
