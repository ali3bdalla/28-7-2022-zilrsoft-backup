<html>
    <head>
{{--        @if(app()->isLocale('ar'))--}}
            <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
            <link rel="stylesheet" href="{{asset('css/rlt/custom.css')}}">
{{--        @endif--}}
            <link rel="stylesheet" href="{{asset('template/css/payment.css')}}">
    </head>


    <body>
        @yield('content')
    </body>
</html>
