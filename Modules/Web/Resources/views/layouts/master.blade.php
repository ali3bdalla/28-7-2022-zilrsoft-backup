<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Module Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @includeIf("layouts.web.styles")
</head>
<body>

<div id="app">
    @includeIf('layouts.web.header')
    <div class="container-fluid">
        @yield('content')
    </div>
</div>
@includeIf("layouts.web.footer")


</body>
</html>
