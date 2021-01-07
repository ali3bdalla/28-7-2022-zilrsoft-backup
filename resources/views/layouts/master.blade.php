<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @includeIf("accounting.layout.head.meta")
    <title>@yield('title',config("app.name"))</title>


    <script defer>
        window.reusable_translator = `@json(trans('reusable'))`;
        window.messages = `@json(trans('messages'))`
    </script>
    @defer_js_asset('accounting/js/rsvp.min.js')
    @defer_js_asset('accounting/js/font_awesome.js')
    @defer_js_asset('js/app.js')


    @yield('sub_defer_javascript')
    @yield('translator')
    @css_asset('lib/css/bootstrap.min.css')
    @css_asset('lib/css/AdminLTE.min.css')
    @css_asset('lib/css/_all-skins.min.css')
    @css_asset('lib/css/buttons.css')
    @css_asset('lib/css/main.css')
    @css_asset('css/main.css')
    @css_asset('lib/css/bootstrap-rtl.css')
    @css_asset('lib/css/AdminLTE-rtl.min.css')
    @css_asset('css/rtl.css')

    @yield('sub_styles')


    @yield('page_css')

    <style type="text/css">
        input {
            direction: ltr !important;
        }
    </style>
</head>
<body class="sidebar-mini skin-blue">
@includeIf("accounting.layout.layout")
@includeIf("accounting.layout.head.js")
@yield('sub_javascript')
@yield('page_js')
</body>
</html>

