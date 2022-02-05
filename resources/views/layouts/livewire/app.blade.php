<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @includeIf("accounting.layout.head.meta")
    <title>@yield('title',config("app.name"))</title>
    @defer_js_asset('accounting/js/font_awesome.js')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    @css_asset('lib/css/AdminLTE.min.css')
    @css_asset('lib/css/_all-skins.min.css')
    <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
    @css_asset('lib/css/AdminLTE-rtl.min.css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @livewireStyles
    @css_asset('css/rtl.css')
    <style>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"/>
<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2({
      placeholder: "Select a State",
      allowClear: true
    });
  });
</script>
@stack('scripts')
</body>
</html>
