<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield("title")</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Font Awesome -->
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/skins/_all-skins.css">
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/skins/_all-skins.css">--}}
<!-- Morris chart -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <!-- jvectormap -->


    <link href="{{ asset("/system/css/lib/select2.min.css")}}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset("/system/css/lib/bootstrap-select.min.css")}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.4/jquery-jvectormap.min.css">
    <!-- Date Picker -->
    <!-- bootstrap wysihtml5 - text editor -->
{{--    <link rel="stylesheet" href="{{ asset("plugins/css/bootstrap3-wysihtml5.min.css") }}">--}}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="{{ asset("system/css/lib/fontawesome.css") }}">
{{--    <link rel="stylesheet" href="{{ asset("system/css/autocomplete.css") }}">--}}
<!-- custom styles -->
    <link rel="stylesheet" href="{{ asset("system/css/lib/datepicker.css")}}"/>

    <link rel="stylesheet" href="{{ asset("system/css/global/plugin.css") }}">
    <link rel="stylesheet" href="{{ asset("system/css/global/sidebar.css") }}">
    <link rel="stylesheet" href="{{ asset("system/css/global/style.css") }}">

<!-- @foreach(\App\Model\Config::locate_custom_masters("ar")["styles"]  as $style)
    @if (stripos(strtolower($style), 'http')!==false)<link rel="stylesheet" href="{{ $style }}">@else<link rel="stylesheet" href="{{ asset($style) }}">@endif
@endforeach
    -->
    <!-- custom styles -->
    @yield("styles")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <input type="hidden" id="base_url" value="{{ url("/") }}">
    <input type="hidden" id="organization_session_id" value="{{  \Session::get("organization_id") }}">
    <input type="hidden" id="employee_session_id" value="{{  \Session::get("employee_id") }}">
    <input type="hidden" id="employee_session_name" value="{{ \Session::get("employee")->name }}">
    @yield("models")
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url("org") }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <!-- <span class="logo-mini"><b>A</b>LT</span> -->
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>ZilrSoft</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">Settings</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                        {{--<li class="user-footer">--}}
                        {{--<a href="{{ route("myinfo") }}" class="btn btn-default btn-flat">General</a>--}}
                        {{--<a href="{{ route("myinfo") }}" class="btn btn-default btn-flat">Permissions</a>--}}

                        {{--<!-- <div class="pull-left"> -->--}}
                        {{--<a href="{{ route("myinfo") }}" class="btn btn-default btn-flat">Inovice</a>--}}
                        {{--<!-- </div> -->--}}
                        {{--<!-- <div class="pull-right">--}}
                        {{--<a href="{{ route("logout") }}" class="btn btn-default btn-flat">Sign out</a>--}}
                        {{--</div> -->--}}
                        {{--</li>--}}
                        <!-- Menu Body -->


                        </ul>
                    </li>


                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="https://cdn.pixabay.com/photo/2015/03/04/22/35/head-659651_960_720.png" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ Session::get("employee")->email }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="https://cdn.pixabay.com/photo/2015/03/04/22/35/head-659651_960_720.png" class="img-circle"
                                     alt="User Image">

                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">

                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        @include('layouts.sidebar')
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield("content")
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> {{ config('app.version') }}
        </div>
        <strong>all copyright saved &copy;</strong>
    </footer>
</div>
</div>



</body>
</html>







<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <link href="http://admincast.com/admincast/preview/html/assets/css/main.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.5.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

</head>
<body class="fixed-navbar">
    <div id="app">
        <div class="page-wrapper">

            @includeIf('layouts.header')
            @includeIf('layouts.sidebar')
             <div class="content-wrapper">
                <div class="page-heading">
                    <h1 class="page-title">@yield('heading-title')</h1>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><i class="la la-home font-20"></i></a>
                        </li>
                        <li class="breadcrumb-item">@yield('heading-description')</li>
                    </ul>
                </div>
                <div class="page-content fade-in-up">
                    @yield('content')
                </div>
                @includeIf('layouts.footer')
            </div>
        </div>
    </div>

     <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.5.0/js/bootstrap4-toggle.min.js"></script>


    <script src="https://kit.fontawesome.com/feb9c89634.js"></script>
    <script src="{{ asset('js/main.js')}}"></script>
</body>
</html>
