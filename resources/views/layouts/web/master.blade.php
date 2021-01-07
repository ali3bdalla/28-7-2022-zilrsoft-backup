<!DOCTYPE html>
<html lang="en" @if(app()->isLocale( 'ar'))dir="rtl"@endif>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title',config('app.name'))</title>
    <script defer src="{{ asset('js/online-store.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @includeIf("layouts.web.styles")
</head>
<body>

<div>
    @includeIf('layouts.web.header')
    <div class="">
        @yield('content')
    </div>
</div>
@includeIf("layouts.web.footer")

<script src="{{ asset('web_assets/template/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery-ui.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.countdown.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.nice-select.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.zoom.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.dd.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.slicknav.js')}}"></script>
<script src="{{ asset('web_assets/template/js/owl.carousel.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/main.js')}}"></script>
<script src="{{ asset('web_assets/js/web.js') }}"></script>

</body>
</html>
