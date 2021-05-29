<!DOCTYPE html>
<html lang="en" @if (app()->isLocale('ar')) dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
       @if(isset($page_title))
           {{ $page_title  }}
        @else
            @if (app()->isLocale('ar')) {{ config('app.ar_title') }} @else {{ config('app.en_title') }} @endif
        @endif
    </title>
    <meta name="title" content="@if(isset($page_title)) {{ $page_title  }}  @else @if (app()->isLocale('ar')) {{ config('app.ar_title') }} @else {{ config('app.en_title') }} @endif @endif">
    <meta name="description" content="{{ __('store.app.description') }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{  config('app.url') }}/">
    <meta property="og:title" content="@if(isset($page_title)) {{ $page_title  }}  @else @if (app()->isLocale('ar')) {{ config('app.ar_title') }} @else {{ config('app.en_title') }} @endif @endif">
    <meta property="og:description" content="{{ __('store.app.description') }}">
    <meta property="og:image" content="{{ config('app.url') }}/images/logo_ar.png">
    <!-- Twitter -->
    <meta property="twitter:card" content="{{ config('app.url') }}/images/logo_ar.png">
    <meta property="twitter:url" content="{{  config('app.url') }}">
    <meta property="twitter:title" content="@if(isset($page_title)) {{ $page_title  }}  @else @if (app()->isLocale('ar')) {{ config('app.ar_title') }} @else {{ config('app.en_title') }} @endif @endif">
    <meta property="twitter:description" content="{{ __('store.app.description') }}">
    <meta property="twitter:image" content="{{ config('app.url') }}/images/logo_ar.png">
    <script defer src="{{ mix('js/online-store.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if (app()->isLocale('ar'))
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css"  integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe"  crossorigin="anonymous"/>
    @else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"  integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"  crossorigin="anonymous">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"  type="text/css">
    <link rel="stylesheet" href="{{ mix('web_assets/template/css/style.min.css') }}" type="text/css">
    @if (app()->isLocale('ar'))
    <link rel="stylesheet" href="{{ mix('css/rtl_store.css') }}">
    @else
    <link rel="stylesheet" href="{{ mix('css/store.css') }}">
    @endif
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('fav/site.webmanifest') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-851059339"></script>
    <script>
      window.dataLayer = window.dataLayer || []

      function gtag () {
        dataLayer.push(arguments)
      }
      gtag('js', new Date())
      gtag('config', 'AW-851059339')
      window.getGooogleTag = gtag
    </script>

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
