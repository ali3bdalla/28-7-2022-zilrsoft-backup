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
<span id="siteseal"><script async type="text/javascript"
                            src="https://seal.godaddy.com/getSeal?sealID=VGW4EB0vH1mdkOke7GWCC4SnXg0eG9NZoqnwFa1Qlp2G9OAjj0c1Sjh4Wgxj"></script></span>
</body>
</html>
