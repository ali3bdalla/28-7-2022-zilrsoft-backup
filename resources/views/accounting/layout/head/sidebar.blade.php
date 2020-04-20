<section class="sidebar" style=" margin-top: 50px;">
    <div class="user-panel">
        <img src="{{ asset(auth()->user()->organization->logo) }}" class="center-block img-responsive"
             alt="{{ auth()->user()->organization->title_ar }}" width="100px" style="">
    </div>

    <ul class="sidebar-menu" data-widget="tree">
        <li class="treeview">
            <a href="#">
                <i class="fa  fa-tachometer-alt"></i> <span>{{ __('sidebar.dashboard') }}</span>

            </a>
            <ul class="treeview-menu">
                <li><a href="{{route('accounting.dashboard.index')}}"><i class="fa fa-tachometer-alt"></i>
                        {{ __('sidebar.dashboard') }}</a></li>
                @can('view accounting')
                    <li>
                        <a href="{{route('accounting.dashboard.index')}}"><i class="fa fa-sun"></i>
                            {{ __('sidebar.statistics') }}
                        </a></li>
                @endcan
            </ul>
        </li>

        @canany(['view item','create item','edit item','manage kit'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span>{{ __('sidebar.management') }} {{ __('sidebar.items')
                    }}</span>

                </a>
                <ul class="treeview-menu">

                    @can('view item')
                        <li>
                            <a href="{{route('accounting.items.index')}}"><i class="fab fa-product-hunt"></i>
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
                            <a href="{{route('accounting.purchases.index')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.purchases') }}
                            </a></li>


                    @endcan
                    @can('confirm purchase')
                        <li>
                            <a href="{{route('accounting.purchases.pending')}}"><i class="fab fa-product-hunt"></i>
                                مشتريات منتظرة
                            </a></li>


                    @endcan
                    @can('view sale')
                        <li>
                            <a href="{{route('accounting.sales.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.sales') }}
                            </a></li>




                    @endcan
                    @can('manage quotation')
                        <li>
                            <a href="{{route('accounting.quotations.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.quotations') }}
                            </a>
                        </li>

                        <li>
                            <a href="{{route('accounting.quotations.services_quotations')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.services_quotations') }}
                            </a>
                        </li>
                    @endcan

                    @can('manage inventory')
                        <li>
                            <a href="{{route('accounting.inventories.beginning.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.beginning_inventory') }}
                            </a></li>

                        {{--                        <li>--}}
                        {{--                            <a href="{{route('accounting.inventories.index')}}"><i class="fa fa-sun"></i>--}}
                        {{--                                {{ __('sidebar.adjust_stock') }}--}}
                        {{--                            </a></li>--}}

                    @endcan
                    <li>
                        <a href="{{route('accounting.reseller_daily.account_close_list')}}"><i class="fa fa-sun"></i>
                            {{ __('sidebar.account_close') }}
                        </a></li>
                    <li>
                        <a href="{{route('accounting.inventories.adjust_stock.index')}}"><i class="fa fa-sun"></i>
                            جرد المخزون
                        </a></li>

                    <li>
                        <a href="{{route('accounting.inventories.inventory_reconciliation')}}"><i class="fa fa-sun"></i>
                            تسوية المخزون
                        </a></li>

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
                            <a href="{{route('accounting.vouchers.index')}}"><i class="fab fa-product-hunt"></i>
                                {{ __('sidebar.vouchers') }}
                            </a></li>


                    @endcan
                    @can('create voucher')
                        <li>
                            <a href="{{route('accounting.vouchers.create')}}?voucher_type=payment"><i class="fa
                            fa-sun"></i>
                                {{ __('pages/vouchers.create_payment') }}
                            </a></li>

                        <li>
                            <a href="{{route('accounting.vouchers.create')}}?voucher_type=receipt"><i class="fa
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
                            <a href="{{route('accounting.accounts.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.chart_of_accounts') }}
                            </a></li>
                    @endcan
                    @can('view transactions')
                        <li>
                            <a href="{{route('accounting.transactions.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.transactions') }}
                            </a></li>
                    @endcan

                    @can('view financial statements')
                        <li>
                            <a href="{{route('accounting.filters.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.financial_statements') }}
                            </a></li>
                    @endcan


                    @can('view charts')
                        <li>
                            <a href="{{route('accounting.accounts.reports.index')}}"><i class="fa fa-sun"></i>
                                تقارير
                            </a></li>
                    @endcan


                </ul>
            </li>
        @endcanany


        @canany(['view reports','create report'])
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> {{ __('sidebar.management') }}
                        {{ __('sidebar.reports') }}</span>
                </a>

                <ul class="treeview-menu">
                    @can('view reports')
                        <li>
                            <a href="{{route('accounting.reports.index')}}"><i class="fa fa-sun"></i>
                                {{ __('sidebar.reports') }}
                            </a></li>
                    @endcan
                    @can('create report')
                        <li>
                            <a href="{{route('accounting.identities.create')}}"><i class="fa fa-sun"></i>
                                {{ __('pages/users.create') }}
                            </a></li>
                    @endcan


                </ul>
            </li>
        @endcanany



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

