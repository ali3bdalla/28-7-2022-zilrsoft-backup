<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title',config("app.name"))</title>

{{--        @defer_js_asset('js/app.js')--}}
    @defer_js_asset('js/upload_images.js')


    @yield('sub_defer_javascript')
    @yield('translator')
    @css_asset('lib/css/bootstrap.min.css')
    {{--    @css_asset('lib/css/AdminLTE.min.css')--}}
    {{--    @css_asset('lib/css/_all-skins.min.css')--}}
    {{--    --}}{{--    @css_asset('lib/css/select2.min.cs.css')--}}
    {{--    @css_asset('lib/css/buttons.css')--}}
    {{--    @css_asset('lib/css/main.css')--}}
    {{--    @css_asset('lib/css/bootstrap-rtl.css')--}}
    {{--    @css_asset('lib/css/AdminLTE-rtl.min.css')--}}
    {{--    @css_asset('css/rtl.css')--}}
    @css_asset('css/tailwind.css')

    <style type="text/css">
        * {
            direction: rtl !important;
        }
    </style>
</head>
<body class="sidebar-mini skin-blue">
@yield('content')

</body>
</html>

