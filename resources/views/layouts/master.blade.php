<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script defer>
        window.reusable_translator = `@json(trans('reusable'))`;
        window.messages = `@json(trans('messages'))`
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="primary-color" content="#0984e3">
    <meta name="second-color" content="#3498db">
    <meta name="app-locate" content="{{ app()->getLocale()  }}">
    <meta name="app-name" content="{{ config('app.name')  }}">
    <meta name="app-base-url" content="{{ route('accounting.dashboard.index')  }}">
    @meta('primary-color','#0984e3')
    @meta('second-color','#3498db')
    @meta('app-locate',{{ app()->getLocale()  }})
    @meta('app-name',{{ config('app.name')  }})
    @meta('app-base-url',{{ route('accounting.dashboard.index')  }})
    @meta('lang-sidebar',@json(trans('sidebar')))
    @meta('lang-pagination',@json(trans('pagination')))
    @meta('lang-messages',@json(trans('messages')))
    @meta('lang-datetime',@json(trans('datetime')))
    @meta('lang-table',@json(trans('table')))
    @meta('lang-reusable',@json(trans('reusable')))
    @meta('lang-validation',@json(trans('validation')))
    @meta('lang-reusable-translator',@json(trans('reusable')))
    @meta('lang-items-page',@json(trans('pages/items')))
    @meta('lang-invoices-page',@json(trans('pages/invoice')))
    @meta('lang-vouchers-page',@json(trans('pages/vouchers')))
    @meta('lang-users-page',@json(trans('pages/users')))
    @meta('lang-filters-page',@json(trans('pages/filters')))
    @meta('lang-branches-page',@json(trans('pages/branches')))
    @meta('lang-categories-page',@json(trans('pages/categories')))
    @meta('datatableBaseUrl','/accounting/datatable/')
    @meta('BaseApiUrl','/accounting/')
    <title>@yield('title',config("app.name"))</title>
    @defer_js_asset('js/app.js')
    @css_asset('lib/css/bootstrap.min.css')
    @css_asset('lib/css/AdminLTE.min.css')
    @css_asset('lib/css/_all-skins.min.css')
    @css_asset('lib/css/select2.min.cs.css')
    @css_asset('lib/css/buttons.css')
    @css_asset('lib/css/main.css')
    @css_asset('lib/css/bootstrap-rtl.css')
    @css_asset('lib/css/AdminLTE-rtl.min.css')
    @css_asset('css/rtl.css')

</head>
<body class="sidebar-mini skin-green">
<div class="wrapper" id="app">
    <?php use App\Invoice;use App\ManagerPrivateTransactions;

    $pending_transactions = ManagerPrivateTransactions::where([['is_pending', true], ['transaction_type',
        'transfer'],
        ['receiver_id', auth()->user()->id]])->with('creator',
        'receiver')->get();

    ?>


    {{--        :can-manage-managers="{{ auth()->user()->canDo('manage managers')}}"--}}
    {{--        :can-view-accounting="{{ auth()->user()->canDo('view accounting')}}"--}}
    {{--        :csrf='@json(csrf_token())'--}}
    {{--        :pending-transactions='@json($pending_transactions)'--}}
    {{--        :pending-purchases='@json($pending_purchases)'--}}
    {{--        :can-confirm-pending-purchases='{{auth()->user()->canDo('confirm purchase')}}'--}}
    {{--        :username='@json(auth()->user()->locale_name)'--}}
    {{--        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"--}}


    <header class="main-header " style="    margin-bottom: 50px;">
        <a href="{{ url('/') }}" class="logo">
            <span class="logo-lg"><b>{{ config('app.name') }}</b></span>
        </a>
        <a class="sidebar-toggle" data-toggle="push-menu" href="#" role="button">
            <span class="sr-only">Menu</span>
            <i class="fa fa-bars"></i>

        </a>
        <nav class="navbar navbar-fixed-top" style="margin-bottom: 50px !important;">
            <div class="right" style="">
                <ul class="nav navbar-nav">

                    @can('confirm purchase')
                        <li>
                            <a class="dropdown-toggle" href="{{ route('purchases.pending') }}">
                               <pending-purchases-counter-component></pending-purchases-counter-component>
                            </a>
                        </li>
                    @endcan
                    <li class="dropdown notifications-menu">
                        <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell" style="font-size: 19px;margin-bottom: -10px;"></i>
                            {{--                            <span class="label label-danger">{{ pendingTransactions.length}}</span>--}}
                        </a>
                        <ul class="dropdown-menu">
                            {{--                            <li class="header">({{ pendingTransactions.length}}) عملية تحويل منتظرة</li>--}}
                            {{--                            <li>--}}
                            {{--                                <!-- inner menu: contains the actual data -->--}}
                            {{--                                <ul class="menu" style="    background: #eee;    max-height: 500px;">--}}

                            {{--                                    <li v-for="transaction in pendingTransactions">--}}
                            {{--                                        <div class="">--}}
                            {{--                                            <div class="panel-body">--}}
                            {{--                                                <i class="fa fa-warning text-yellow"></i> تحويل من {{--}}
                            {{--                                                transaction.creator.locale_name }} ،--}}
                            {{--                                                بمبلغ--}}
                            {{--                                                <span class="text-primary" style="    font-weight: bold;">{{--}}
                            {{--                                                    parseFloat(transaction.amount).toFixed(2)--}}
                            {{--                                                    }}</span>--}}
                            {{--                                            </div>--}}
                            {{--                                            <div class="panel-footer">--}}
                            {{--                                                <a--}}
                            {{--                                                        :href="'/accounting/reseller_daily/'+transaction.id+'/confirm_transaction'"--}}
                            {{--                                                        class="btn btn-custom-primary pull-left">موافق</a>--}}
                            {{--                                                <a :href="'/accounting/reseller_daily/'+transaction.id+--}}
                            {{--                                                   '/delete_transaction'"--}}
                            {{--                                                   class="btn btn-custom-default">الغاء </a>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </li>--}}

                            {{--                                </ul>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="footer"><a href="#">View all</a></li>--}}
                        </ul>
                    </li>
                    <li class="dropdown user user-menu  dropdown-menu-right pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <img alt="User Image" class="user-image"
                                 src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">
                            <span class="hidden-xs"></span>
                            {{ auth()->user()->locale_name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img alt="User Image" class="img-circle"
                                     src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">
                                <p>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a class="btn btn-default btn-flat" href="">@lang('layouts.profile')</a>
                                </div>
                                <div class="pull-right">
                                    @includeIf('layouts.buttons.logout_button')
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ route('configuration.index') }}">@lang('layouts.configuration')</a></li>
                    <li><a href="{{ route('configuration.hardware')}}">@lang('layouts.hardware_configuration')</a></li>
                    <li><a href="{{ route('statistics.index')}}">@lang('layouts.statistics')</a></li>
                    <li><a href="{{ route('items.index') }}"> @lang('layouts.items')</a></li>
                    <li><a href="{{ route('sales.create') }}">@lang('layouts.create_sales')</a></li>
                    <li><a href="{{ route('purchases.create') }}">@lang('layouts.create_purchases')</a></li>
                    <li><a href="{{ route('daily.index') }}"> @lang('layouts.daily')</a></li>
                </ul>
            </div>
        </nav>
    </header>


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

@yield('page_js')
</body>
</html>
