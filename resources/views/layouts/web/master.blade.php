<!DOCTYPE html>
<html lang="en" @if (app()->isLocale('ar')) dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (app()->isLocale('ar')) متجر المسبار @else Almesbar Shop @endif
    </title>
    <script defer src="{{ asset('js/online-store.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if (app()->isLocale('ar'))
        <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css"
              integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe"
              crossorigin="anonymous"/>
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
              integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"
              crossorigin="anonymous">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
          type="text/css">
    <link rel="stylesheet" href="{{ asset('web_assets/template/css/style.min.css') }}" type="text/css">


    @if (app()->isLocale('ar'))
        <link rel="stylesheet" href="{{ asset('css/rtl_store.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/store.css') }}">
    @endif
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('fav/site.webmanifest') }}">
    <!-- Event snippet for عملية شراء conversion page
In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
    <!-- Global site tag (gtag.js) - Google Ads: 851059339 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-851059339"></script>
    <script>
      window.dataLayer = window.dataLayer || []

      function gtag () {
        dataLayer.push(arguments)
      }

      gtag('js', new Date())

      gtag('config', 'AW-851059339')

      window.getGooogleTag = gtag
      // function gtag_report_conversion (url) {
      //   var callback = function () {
      //     if (typeof (url) != 'undefined') {
      //       window.location = url
      //     }
      //   }
      //   gtag('event', 'conversion', {
      //     'send_to': 'AW-851059339/U_sVCLfmhI0CEIvF6JUD',
      //     'transaction_id': '',
      //     'event_callback': callback
      //   })
      //   return false
      // }
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
