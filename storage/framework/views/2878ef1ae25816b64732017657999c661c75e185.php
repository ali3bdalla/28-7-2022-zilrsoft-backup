<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle"
                 alt="User Image">
        </div>
        <div class="pull-left info">
            <p><?php echo e(Auth::user()->name); ?></p>
            <p><?php echo e(Auth::user()->memebership()); ?></p>
        </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

        <li class="header"><?php echo e(__('sidebar.dashboard')); ?></li>
        <sidebar-item-component

                url='<?php echo e(route('management.dashboard')); ?>'
                title='<?php echo e(__('sidebar.dashboard')); ?>'
                icon='fa fa-tachometer-alt'
        >

        </sidebar-item-component>

        <li class="header"><?php echo e(__('sidebar.items')); ?></li>
        <!-- starting of items header-->
        <sidebar-item-component

                url='<?php echo e(route('management.items.index')); ?>'
                title='<?php echo e(__('sidebar.items')); ?>'
                icon='fab fa-product-hunt'
        >
        </sidebar-item-component>
        <?php if(!in_array(auth()->user()->id,config('managers.pending'))): ?>
            <sidebar-item-component
                    url='<?php echo e(route('management.items.pending')); ?>'
                    title='<?php echo e(__('sidebar.pending_items')); ?>'
                    icon='fab fa-periscope'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='<?php echo e(route('management.kits.index')); ?>'
                    title='<?php echo e(__('sidebar.kits')); ?>'
                    icon='fa fa-quote-right'
            >
            </sidebar-item-component>
            <sidebar-item-component
                    url='<?php echo e(route('management.categories.index')); ?>'
                    title='<?php echo e(__('sidebar.categories')); ?>'
                    icon='fa fa-th'
            >
            </sidebar-item-component>


            <sidebar-item-component
                    url='<?php echo e(route('management.filters.index')); ?>'
                    title='<?php echo e(__('sidebar.filters')); ?>'
                    icon='fa fa-crop-alt'
            >
            </sidebar-item-component>


            <sidebar-item-component

                    url='<?php echo e(route('management.items.barcode.index')); ?>'
                    title='<?php echo e(__('sidebar.barcode')); ?>'
                    icon='fab fa-product-hunt'
            >
            </sidebar-item-component>


            <sidebar-item-component
                    url='<?php echo e(route('management.serial_history.index')); ?>'
                    title='<?php echo e(__('sidebar.serial_history')); ?>'
                    icon='fa fa-clock'
            >
            </sidebar-item-component>
            <!-- ending of items header-->


            <li class="header"><?php echo e(__('sidebar.invoices')); ?></li>
            <!-- starting of invoices header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.purchases.index')); ?>'
                    title='<?php echo e(__('sidebar.purchases')); ?>'
                    icon='fa fa-shopping-bag'
            >
            </sidebar-item-component>



            <sidebar-item-component
                    url='<?php echo e(route('management.sales.index')); ?>'
                    title='<?php echo e(__('sidebar.sales')); ?>'
                    icon='fab fa-amazon-pay'
            >
            </sidebar-item-component>



            <!-- ending of invoices header-->
            <li class="header"><?php echo e(__('sidebar.inventory')); ?></li>
            <!-- starting of evenoty counter header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.inventories.beginning.index')); ?>'
                    title='<?php echo e(__('sidebar.beginning_inventory')); ?>'
                    icon='fa fa-sync'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='<?php echo e(route('management.inventories.index')); ?>'
                    title='<?php echo e(__('sidebar.inventory')); ?>'
                    icon='fa fa-warehouse'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='<?php echo e(route('management.inventories.index')); ?>'
                    title='<?php echo e(__('sidebar.adjust_stock')); ?>'
                    icon='fa fa-layer-group'
            >
            </sidebar-item-component>

            <!-- ending of evenoty counter header-->


            <li class="header"><?php echo e(__('sidebar.vouchers')); ?></li>
            <!-- starting of payments header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.payments.index')); ?>'
                    title='<?php echo e(__('sidebar.vouchers')); ?>'
                    icon='fa fa-money-bill'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='<?php echo e(route('management.payments.payments')); ?>'
                    title='<?php echo e(__('sidebar.payments')); ?>'
                    icon='fa fa-file-invoice'
            >
            </sidebar-item-component>

            <sidebar-item-component
                    url='<?php echo e(route('management.payments.receipts')); ?>'
                    title='<?php echo e(__('sidebar.receipts')); ?>'
                    icon='fa fa-receipt'
            >
            </sidebar-item-component>



            <li class="header"><?php echo e(__('sidebar.accounting')); ?></li>
            <!-- starting of users header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.charts.index')); ?>'
                    title='<?php echo e(__('sidebar.chart_of_accounts')); ?>'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>


            <sidebar-item-component
                    url='<?php echo e(route('management.users.index')); ?>'
                    title='<?php echo e(__('sidebar.general_ledger')); ?>'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>


            <sidebar-item-component
                    url='<?php echo e(route('management.users.index')); ?>'
                    title='<?php echo e(__('sidebar.journal_entry')); ?>'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>


            <sidebar-item-component
                    url='<?php echo e(route('management.users.index')); ?>'
                    title='<?php echo e(__('sidebar.financial_statements')); ?>'
                    icon='fa fa-bars'
            >

            </sidebar-item-component>






            <!-- ending of payments header-->
            <li class="header"><?php echo e(__('sidebar.users')); ?></li>
            <!-- starting of users header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.users.index')); ?>'
                    title='<?php echo e(__('sidebar.users')); ?>'
                    icon='fa fa-users'
            >
            </sidebar-item-component>
            <!-- ending of users header-->

            <li class="header"><?php echo e(__('sidebar.reports')); ?></li>
            <!-- starting of reports header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.reports.index')); ?>'
                    title='<?php echo e(__('sidebar.reports')); ?>'
                    icon='fa fa-registered'
            >
            </sidebar-item-component>
            <!-- ending of reports header-->

            <li class="header"><?php echo e(__('sidebar.settings')); ?></li>
            <!-- starting of settings header-->
            <sidebar-item-component
                    url='<?php echo e(route('management.branches.index')); ?>'
                    title='<?php echo e(__('sidebar.branches')); ?>'
                    icon='fa fa-code-branch'
            >
            </sidebar-item-component>
            
            
            
            
            
            

            
            
            
            
            
            


            <sidebar-item-component
                    url='<?php echo e(route('management.settings.index')); ?>'
                    title='<?php echo e(__('sidebar.settings')); ?>'
                    icon='fa fa-cogs'
            >
            </sidebar-item-component>
            <!-- ending of settings header-->

        <?php endif; ?>
    </ul>
</section>

<?php /**PATH /usr/local/var/www/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>