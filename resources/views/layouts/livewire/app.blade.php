<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @includeIf("accounting.layout.head.meta")
    <title>@yield('title',config("app.name"))</title>
    @defer_js_asset('accounting/js/font_awesome.js')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></link>
    @css_asset('lib/css/AdminLTE.min.css')
    @css_asset('lib/css/_all-skins.min.css')
    <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css"></link>
    @css_asset('lib/css/AdminLTE-rtl.min.css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"></link>
    @livewireStyles
    @css_asset('css/rtl.css')
    <style>
        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-container--default .select2-results__option, .select2-container--default .select2-results__option--highlighted.select2-results__option, .select2-container .select2-selection--single .select2-selection__rendered {
            text-align: right !important;
        }

        input {
            text-align: center !important;
        }
    </style>

</head>

<body class="sidebar-mini skin-blue">
<div class="wrapper" id="app">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('buttons')</small>
            </h1>
        </section>
        <div class="content">
            {{ $slot }}
        </div>
    </div>
    <aside class="main-sidebar" style="z-index:108;padding-top: 0px !important;">
        @includeIf('layouts.livewire.sidebar')
    </aside>
</div>
@livewireScripts
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $('.js-example-basic-single').select2({
    allowClear: true
  })
</script>
@stack('scripts')
</body>
</html>
