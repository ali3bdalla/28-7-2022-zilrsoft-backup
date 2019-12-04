<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle"
                 alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{Auth::user()->name}}</p>
            <p>{{Auth::user()->memebership()}}</p>
        </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

        <li class="header">{{ __('sidebar.dashboard') }}</li>
        <sidebar-item-component

                url='{{route('management.dashboard')}}'
                title='{{ __('sidebar.dashboard') }}'
                icon='fa fa-tachometer-alt'
        >

        </sidebar-item-component>

        <li class="header">{{ __('sidebar.items') }}</li>
        <!-- starting of items header-->
        <sidebar-item-component

                url='{{route('management.items.index')}}'
                title='{{ __('sidebar.items') }}'
                icon='fab fa-product-hunt'
        >
        </sidebar-item-component>
        @if(!in_array(auth()->user()->id,config('managers.pending')))
            <sidebar-item-component
                    url='{{route('management.items.pending')}}'
                    title='{{ __('sidebar.pending_items') }}'
                    icon='fab fa-periscope'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='{{route('management.kits.index')}}'
                    title='{{ __('sidebar.kits') }}'
                    icon='fa fa-quote-right'
            >
            </sidebar-item-component>
            <sidebar-item-component
                    url='{{route('management.categories.index')}}'
                    title='{{ __('sidebar.categories') }}'
                    icon='fa fa-th'
            >
            </sidebar-item-component>


            <sidebar-item-component
                    url='{{route('management.filters.index')}}'
                    title='{{ __('sidebar.filters') }}'
                    icon='fa fa-crop-alt'
            >
            </sidebar-item-component>


            <sidebar-item-component

                    url='{{route('management.items.barcode.index')}}'
                    title='{{ __('sidebar.barcode') }}'
                    icon='fab fa-product-hunt'
            >
            </sidebar-item-component>


            <sidebar-item-component
                    url='{{route('management.serial_history.index')}}'
                    title='{{ __('sidebar.serial_history') }}'
                    icon='fa fa-clock'
            >
            </sidebar-item-component>
            <!-- ending of items header-->


            <li class="header">{{ __('sidebar.invoices') }}</li>
            <!-- starting of invoices header-->
            <sidebar-item-component
                    url='{{route('management.purchases.index')}}'
                    title='{{ __('sidebar.purchases') }}'
                    icon='fa fa-shopping-bag'
            >
            </sidebar-item-component>



            <sidebar-item-component
                    url='{{route('management.sales.index')}}'
                    title='{{ __('sidebar.sales') }}'
                    icon='fab fa-amazon-pay'
            >
            </sidebar-item-component>


            <sidebar-item-component
                    url='{{route('management.sales.quotations')}}'
                    title='{{ __('sidebar.quotations') }}'
                    icon='fa fa-eye'
            >
            </sidebar-item-component>



            <!-- ending of invoices header-->
            <li class="header">{{ __('sidebar.inventory') }}</li>
            <!-- starting of evenoty counter header-->
            <sidebar-item-component
                    url='{{route('management.inventories.beginning.index')}}'
                    title='{{ __('sidebar.beginning_inventory') }}'
                    icon='fa fa-sync'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='{{route('management.inventories.index')}}'
                    title='{{ __('sidebar.inventory') }}'
                    icon='fa fa-warehouse'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='{{route('management.inventories.index')}}'
                    title='{{ __('sidebar.adjust_stock') }}'
                    icon='fa fa-layer-group'
            >
            </sidebar-item-component>

            <!-- ending of evenoty counter header-->


            <li class="header">{{ __('sidebar.vouchers') }}</li>
            <!-- starting of payments header-->
            <sidebar-item-component
                    url='{{route('management.payments.index')}}'
                    title='{{ __('sidebar.vouchers') }}'
                    icon='fa fa-money-bill'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='{{route('management.payments.payments')}}'
                    title='{{ __('sidebar.payments') }}'
                    icon='fa fa-file-invoice'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='{{route('management.payments.receipts')}}'
                    title='{{ __('sidebar.receipts') }}'
                    icon='fa fa-receipt'
            >
            </sidebar-item-component>



            <li class="header">{{ __('accounts') }}</li>
            <!-- starting of users header-->
            <sidebar-item-component
                    url='{{route('management.accounts.index')}}'
                    title='{{ __('sidebar.chart_of_accounts') }}'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>


            <sidebar-item-component
                    url='{{route('management.users.index')}}'
                    title='{{ __('sidebar.general_ledger') }}'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>


            <sidebar-item-component
                    url='{{route('management.transactions.index')}}'
                    title='{{ __('sidebar.journal_entry') }}'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>


            <sidebar-item-component
                    url='{{route('management.financial_statements.index')}}'
                    title='{{ __('sidebar.financial_statements') }}'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>







            <!-- ending of payments header-->
            <li class="header">{{ __('sidebar.users') }}</li>
            <!-- starting of users header-->
            <sidebar-item-component
                    url='{{route('management.users.index')}}'
                    title='{{ __('sidebar.users') }}'
                    icon='fa fa-users'
            >
            </sidebar-item-component>
            <!-- ending of users header-->

            <li class="header">{{ __('sidebar.reports') }}</li>
            <!-- starting of reports header-->
            <sidebar-item-component
                    url='{{route('management.reports.index')}}'
                    title='{{ __('sidebar.reports') }}'
                    icon='fa fa-registered'
            >
            </sidebar-item-component>
            <!-- ending of reports header-->

            <li class="header">{{ __('sidebar.settings') }}</li>
            <!-- starting of settings header-->
            <sidebar-item-component
                    url='{{route('management.branches.index')}}'
                    title='{{ __('sidebar.branches') }}'
                    icon='fa fa-code-branch'
            >
            </sidebar-item-component>
            {{--        <sidebar-item-component--}}
            {{--            url='{{route('management.expenses.index')}}'--}}
            {{--            title='{{ __('sidebar.expenses') }}'--}}
            {{--            icon='fa fa-sync'--}}
            {{--        >--}}
            {{--        </sidebar-item-component>--}}

            {{--        <sidebar-item-component--}}
            {{--            url='{{route('management.gateways.index')}}'--}}
            {{--            title='{{ __('sidebar.payments_methods') }}'--}}
            {{--            icon='fa fa-gem'--}}
            {{--        >--}}
            {{--        </sidebar-item-component>--}}


            <sidebar-item-component
                    url='{{ route('management.settings.index')}}'
                    title='{{ __('sidebar.settings') }}'
                    icon='fa fa-cogs'
            >
            </sidebar-item-component>
            <!-- ending of settings header-->

        @endif
    </ul>
</section>

