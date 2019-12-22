<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @includeIf("accounting.layout.head.meta")
    <title>@yield('title',config("app.name"))</title>
    @yield('translator')
    @includeIf("accounting.layout.head.defer_js")
    @includeIf("accounting.layout.head.css")
    @yield('page_css')
</head>
<body class="sidebar-mini skin-blue sidebar-collapse">
@includeIf("accounting.layout.layout")
@includeIf("accounting.layout.head.js")
@yield('page_js')
</body>
</html>
