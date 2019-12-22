<div class="wrapper" id="app">
    @includeIf('accounting.layout.head.header')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('buttons')</small>
            </h1>
        </section>
        <div class="content">
            @yield("before_content")
            @yield('content')
            @yield("after_content")
        </div>
    </div>
    <aside class="main-sidebar" style="z-index:108;padding-top: 0px !important;">
        @includeIf('accounting.layout.head.sidebar')
    </aside>
    <footer class="main-footer">
        @includeIf('layouts.footer')
    </footer>
</div>