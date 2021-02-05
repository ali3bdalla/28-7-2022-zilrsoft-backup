<!DOCTYPE html>
<html lang="en" @if(app()->isLocale( 'ar'))dir="rtl"@endif>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @yield('title',config('app.name')) --}}
    <title>@if(app()->isLocale('ar')) متجر المسبار @else Almesbar Shop @endif</title>
    <script defer src="{{ asset('js/online-store.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="{{ asset('web_assets/template/css/bootstrap.min.css')}}" type="text/css">
<link rel="stylesheet" href="{{ asset('web_assets/template/css/font-awesome.min.css')}}" type="text/css">
<link rel="stylesheet" href="{{ asset('web_assets/template/css/style.css')}}" type="text/css">
<link rel="preconnect" href="https://fonts.gstatic.com">
{{-- <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ asset('css/store.css') }}">

<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fav/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('fav/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('fav/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('fav/site.webmanifest') }}">

@if(app()->isLocale( 'ar'))
    {{-- <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@600&family=Tajawal:wght@300;500&display=swap" rel="stylesheet"> --}}
    <link
  rel="stylesheet"
  href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css"
  integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe"
  crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/ar/store.css') }}">
@endif
</head>
<body>

<div>
    @includeIf('layouts.web.header')
    <div class="">
        @yield('content')
    </div>
</div>

</body>
</html>
