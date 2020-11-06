<section class="sidebar" style=" margin-top: 50px;">
    <div class="user-panel">
        <img src="{{ asset(auth()->user()->organization->logo) }}" class="center-block img-responsive"
             alt="{{ auth()->user()->organization->title_ar }}" width="100px" style="">
        <h3 class="p-3 text-white btn btn-primary">{{ auth()->user()->organization->title_ar }}</h3>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
        {{--        <li class="treeview">--}}
        {{--            <a href="#">--}}
        {{--                <i class="fa  fa-tachometer-alt"></i> <span>{{ __('sidebar.dashboard') }}</span>--}}

        {{--            </a>--}}
        {{--            <ul class="treeview-menu">--}}
        {{--                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-tachometer-alt"></i>--}}
        {{--                        {{ __('sidebar.dashboard') }}</a></li>--}}
        {{--                @can('view accounting')--}}
        {{--                    <li>--}}
        {{--                        <a href="{{route('dashboard.index')}}"><i class="fa fa-sun"></i>--}}
        {{--                            {{ __('sidebar.statistics') }}--}}
        {{--                        </a></li>--}}
        {{--                @endcan--}}
        {{--            </ul>--}}
        {{--        </li>--}}

        @canany(['view item','create item','edit item','manage kit'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span>{{ __('sidebar.management') }} {{ __('sidebar.items')
                    }}</span>

                </a>
                <ul class="treeview-menu">

                    @can('view item')
                        <li>
                            <a href="{{route('items.index')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.items') }}
                            </a></li>

                        <li>
                            <a href="{{route('accounting.items.barcode')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.barcode') }}
                            </a></li>



                        <li>
                            <a href="{{route('accounting.items.serial_activities')}}"><i
                                        class="fab fa-product-hunt"></i>
                                {{ __('sidebar.serial_history') }}
                            </a></li>
                    @endcan
                    @can('manage kit')
                        <li>
                            <a href="{{route('accounting.kits.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.kits') }}
                            </a></li>
                    @endcan


                </ul>
            </li>
        @endcanany



        @canany(['view category','create category','edit category','view filter','create filter','edit filter'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.categories') }}</span>
                </a>
                <ul class="treeview-menu">

                    @can('view category')
                        <li>
                            <a href="{{route('accounting.categories.index')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.categories') }}
                            </a></li>


                    @endcan
                    @can('view filter')
                        <li>
                            <a href="{{route('accounting.filters.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.filters') }}
                            </a></li>
                    @endcan


                </ul>
            </li>
        @endcanany

        @canany(['view sale','create sale','edit sale','view purchase','create purchase','edit purchase','confirm
        purchase'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.invoices') }}</span>
                </a>
                <ul class="treeview-menu">

                    @can('view purchase')
                        <li>
                            <a href="{{route('purchases.index')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.purchases') }}
                            </a></li>


                    @endcan
                    {{--                    @can('confirm purchase')--}}
                    {{--                        <li>--}}
                    {{--                            <a href="{{route('purchases.pending')}}"><i class="fab fa-product-hunt"></i>--}}
                    {{--                                مشتريات معلقة--}}
                    {{--                            </a></li>--}}


                    {{--                    @endcan--}}
                    @can('view sale')
                        <li>
                            <a href="{{route('sales.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.sales') }}
                            </a></li>




                    @endcan
                    @can('manage quotation')
                        <li>
                            <a href="{{route('sales.drafts.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.quotations') }}
                            </a>
                        </li>


                        <li>
                            <a href="{{route('orders.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.orders') }}
                            </a>
                        </li>


                        <li>
                            <a href="{{route('sales.drafts.create.service')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.services_quotations') }}
                            </a>
                        </li>
                    @endcan

                    @can('manage inventory')
                        <li>
                            <a href="{{route('inventory.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.beginning_inventory') }}
                            </a></li>

                        {{--                        <li>--}}
                        {{--                            <a href="{{route('accounting.inventories.index')}}"><i class="fa fa-sun"></i>--}}
                        {{--                                {{ __('sidebar.adjust_stock') }}--}}
                        {{--                            </a></li>--}}

                    @endcan
                    {{--                    <li>--}}
                    {{--                        <a href="{{route('accounting.reseller_daily.account_close_list')}}"><i class="fa fa-sun"></i>--}}
                    {{--                            {{ __('sidebar.account_close') }}--}}
                    {{--                        </a></li>--}}
                    {{--                    <li>--}}
                    {{--                        <a href="{{route('accounting.inventories.adjust_stock.index')}}"><i class="fa fa-sun"></i>--}}
                    {{--                            جرد المخزون--}}
                    {{--                        </a></li>--}}

                    {{--                    <li>--}}
                    {{--                        <a href="{{route('accounting.inventories.inventory_reconciliation')}}"><i class="fa fa-sun"></i>--}}
                    {{--                            تسوية المخزون--}}
                    {{--                        </a></li>--}}

                </ul>
            </li>
        @endcanany



        @canany(['view voucher','create voucher','edit voucher'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.vouchers') }}</span>
                </a>
                <ul class="treeview-menu">

                    @can('view voucher')
                        <li>
                            <a href="{{route('vouchers.index')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.vouchers') }}
                            </a></li>


                    @endcan
                    @can('create voucher')
                        <li>
                            <a href="{{route('vouchers.create')}}?voucher_type=payment"><i class="fa
                            fa-sun"></i>
                                {{ __('pages/vouchers.create_payment') }}
                            </a></li>

                        <li>
                            <a href="{{route('vouchers.create')}}?voucher_type=receipt"><i class="fa
                            fa-sun"></i>
                                {{ __('pages/vouchers.create_receipt') }}
                            </a></li>
                    @endcan


                </ul>
            </li>
        @endcanany

        @canany(['view charts','view transactions','view financial statements'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.accounts') }}</span>
                </a>

                <ul class="treeview-menu">
                    @can('view charts')
                        <li>
                            <a href="{{route('accounts.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.chart_of_accounts') }}
                            </a></li>

                        <li>
                            <a href="{{route('financial_statements.trial_balance')}}"><i class="fa fa-sun"></i>
                                ميزان المراجعة
                            </a></li>
                        {{--                        <li>--}}
                        {{--                            <a href="{{route('financial_statements.index')}}"><i class="fa fa-sun"></i>--}}
                        {{--                               القوائم المالية--}}
                        {{--                            </a></li>--}}
                    @endcan
                    @can('view transactions')
                        <li>
                            <a href="{{route('entities.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.transactions') }}
                            </a></li>
                    @endcan

                    <li><a href="{{ route('accounts.reports.index') }}">
                            <svg aria-hidden="true" data-prefix="fa" data-icon="sun" role="img"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""
                                 class="svg-inline--fa fa-sun fa-w-16">
                                <path fill="currentColor"
                                      d="M274.835 12.646l25.516 62.393c4.213 10.301 16.671 14.349 26.134 8.492l57.316-35.479c15.49-9.588 34.808 4.447 30.475 22.142l-16.03 65.475c-2.647 10.81 5.053 21.408 16.152 22.231l67.224 4.987c18.167 1.348 25.546 24.057 11.641 35.826L441.81 242.26c-8.495 7.19-8.495 20.289 0 27.479l51.454 43.548c13.906 11.769 6.527 34.478-11.641 35.826l-67.224 4.987c-11.099.823-18.799 11.421-16.152 22.231l16.03 65.475c4.332 17.695-14.986 31.73-30.475 22.142l-57.316-35.479c-9.463-5.858-21.922-1.81-26.134 8.492l-25.516 62.393c-6.896 16.862-30.774 16.862-37.67 0l-25.516-62.393c-4.213-10.301-16.671-14.349-26.134-8.492l-57.317 35.479c-15.49 9.588-34.808-4.447-30.475-22.142l16.03-65.475c2.647-10.81-5.053-21.408-16.152-22.231l-67.224-4.987c-18.167-1.348-25.546-24.057-11.641-35.826L70.19 269.74c8.495-7.19 8.495-20.289 0-27.479l-51.454-43.548c-13.906-11.769-6.527-34.478 11.641-35.826l67.224-4.987c11.099-.823 18.799-11.421 16.152-22.231l-16.03-65.475c-4.332-17.695 14.986-31.73 30.475-22.142l57.317 35.479c9.463 5.858 21.921 1.81 26.134-8.492l25.516-62.393c6.896-16.861 30.774-16.861 37.67 0zM392 256c0-74.991-61.01-136-136-136-74.991 0-136 61.009-136 136s61.009 136 136 136c74.99 0 136-61.009 136-136zm-32 0c0 57.346-46.654 104-104 104s-104-46.654-104-104 46.654-104 104-104 104 46.654 104 104z"></path>
                            </svg>
                            تقارير
                        </a></li>


                </ul>
            </li>
        @endcanany


        {{--        @canany(['view reports','create report'])--}}
        {{--            <li class="treeview">--}}
        {{--                <a href="#">--}}
        {{--                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}--}}
        {{--                        {{ __('sidebar.reports') }}</span>--}}
        {{--                </a>--}}

        {{--                <ul class="treeview-menu">--}}
        {{--                    @can('view reports')--}}
        {{--                        <li>--}}
        {{--                            <a href="{{route('accounting.reports.index')}}"><i class="fa fa-sun"></i>--}}
        {{--                                {{ __('sidebar.reports') }}--}}
        {{--                            </a></li>--}}
        {{--                    @endcan--}}
        {{--                    @can('create report')--}}
        {{--                        <li>--}}
        {{--                            <a href="{{route('accounting.identities.create')}}"><i class="fa fa-sun"></i>--}}
        {{--                                {{ __('pages/users.create') }}--}}
        {{--                            </a></li>--}}
        {{--                    @endcan--}}


        {{--                </ul>--}}
        {{--            </li>--}}
        {{--        @endcanany--}}



        @canany(['view identities','create identity','manage managers'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.users') }}</span>
                </a>

                <ul class="treeview-menu">
                    @can('view identity')
                        <li>
                            <a href="{{route('accounting.identities.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.users') }}
                            </a></li>
                    @endcan
                    @can('create identity')
                        <li>
                            <a href="{{route('accounting.identities.create')}}"><i class="fa fa-sun"></i>
                                {{ __('pages/users.create') }}
                            </a></li>
                    @endcan
                    @can('manage managers')
                        <li>
                            <a href="{{route('accounting.managers.index')}}"><i class="fa fa-sun"></i>
                                {{ __('pages/users.managers') }}
                            </a></li>
                        <li>
                            <a href="{{route('accounting.managers.create')}}"><i class="fa fa-sun"></i>
                                {{ __('pages/users.create_manager') }}
                            </a></li>

                    @endcan


                </ul>
            </li>
        @endcanany

        @canany(['manage settings','manage branches','manage expenses'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.settings') }}</span>
                </a>

                <ul class="treeview-menu">
                    @can('manage settings')
                        <li>
                            <a href="{{route('accounting.settings.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.settings') }}
                            </a></li>
                    @endcan
                    @can('manage branches')
                        <li>
                            <a href="{{route('accounting.branches.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.branches') }}
                            </a></li>
                    @endcan
                    @can('manage expenses')
                        <li>
                            <a href="{{route('accounting.expenses.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.expenses') }}
                            </a></li>
                    @endcan


                </ul>
            </li>
        @endcanany


    </ul>
</section>

