<section class="sidebar" style=" margin-top: 50px;">
    <div class="user-panel">
        <img src="<?php echo e(asset(auth()->user()->organization->logo)); ?>" class="center-block img-responsive"
             alt="<?php echo e(auth()->user()->organization->title_ar); ?>" width="100px" style="">
    </div>

    <ul class="sidebar-menu" data-widget="tree">
        <li class="treeview">
            <a href="#">
                <i class="fa  fa-tachometer-alt"></i> <span><?php echo e(__('sidebar.dashboard')); ?></span>

            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo e(route('accounting.dashboard.index')); ?>"><i class="fa fa-tachometer-alt"></i>
                        <?php echo e(__('sidebar.dashboard')); ?></a></li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view accounting')): ?>
                    <li>
                        <a href="<?php echo e(route('accounting.dashboard.index')); ?>"><i class="fa fa-sun"></i>
                            <?php echo e(__('sidebar.statistics')); ?>

                        </a></li>
                <?php endif; ?>
            </ul>
        </li>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view item','create item','edit item','manage kit'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span><?php echo e(__('sidebar.management')); ?> <?php echo e(__('sidebar.items')); ?></span>

                </a>
                <ul class="treeview-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view item')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.items.index')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.items')); ?>

                            </a></li>

                        <li>
                            <a href="<?php echo e(route('accounting.items.barcode')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.barcode')); ?>

                            </a></li>



                        <li>
                            <a href="<?php echo e(route('accounting.items.serial_activities')); ?>"><i
                                        class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.serial_history')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage kit')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.kits.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.kits')); ?>

                            </a></li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>



        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view category','create category','edit category','view filter','create filter','edit filter'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.categories')); ?></span>
                </a>
                <ul class="treeview-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view category')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.categories.index')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.categories')); ?>

                            </a></li>


                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view filter')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.filters.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.filters')); ?>

                            </a></li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view sale','create sale','edit sale','view purchase','create purchase','edit purchase','confirm
        purchase'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.invoices')); ?></span>
                </a>
                <ul class="treeview-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view purchase')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.purchases.index')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.purchases')); ?>

                            </a></li>


                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('confirm purchase')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.purchases.pending')); ?>"><i class="fab fa-product-hunt"></i>
                                مشتريات منتظرة
                            </a></li>


                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view sale')): ?>
                        <li>
                            <a href="<?php echo e(route('sales.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.sales')); ?>

                            </a></li>




                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage quotation')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.quotations.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.quotations')); ?>

                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('accounting.quotations.services_quotations')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.services_quotations')); ?>

                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage inventory')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.inventories.beginning.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.beginning_inventory')); ?>

                            </a></li>

                        
                        
                        
                        

                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('accounting.reseller_daily.account_close_list')); ?>"><i class="fa fa-sun"></i>
                            <?php echo e(__('sidebar.account_close')); ?>

                        </a></li>
                    <li>
                        <a href="<?php echo e(route('accounting.inventories.adjust_stock.index')); ?>"><i class="fa fa-sun"></i>
                            جرد المخزون
                        </a></li>

                    <li>
                        <a href="<?php echo e(route('accounting.inventories.inventory_reconciliation')); ?>"><i class="fa fa-sun"></i>
                            تسوية المخزون
                        </a></li>

                </ul>
            </li>
        <?php endif; ?>



        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view voucher','create voucher','edit voucher'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.vouchers')); ?></span>
                </a>
                <ul class="treeview-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view voucher')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.vouchers.index')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.vouchers')); ?>

                            </a></li>


                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create voucher')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.vouchers.create')); ?>?voucher_type=payment"><i class="fa
                            fa-sun"></i>
                                <?php echo e(__('pages/vouchers.create_payment')); ?>

                            </a></li>

                        <li>
                            <a href="<?php echo e(route('accounting.vouchers.create')); ?>?voucher_type=receipt"><i class="fa
                            fa-sun"></i>
                                <?php echo e(__('pages/vouchers.create_receipt')); ?>

                            </a></li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view charts','view transactions','view financial statements'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.accounts')); ?></span>
                </a>

                <ul class="treeview-menu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view charts')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.accounts.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.chart_of_accounts')); ?>

                            </a></li>

                        <li>
                            <a href="<?php echo e(route('accounting.trial_balance.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.financial_statements')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view transactions')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.transactions.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.transactions')); ?>

                            </a></li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view financial statements')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.filters.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.financial_statements')); ?>

                            </a></li>
                    <?php endif; ?>


                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view charts')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.accounts.reports.index')); ?>"><i class="fa fa-sun"></i>
                                تقارير
                            </a></li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>


        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view reports','create report'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.reports')); ?></span>
                </a>

                <ul class="treeview-menu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view reports')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.reports.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.reports')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create report')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.identities.create')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('pages/users.create')); ?>

                            </a></li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>



        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view identities','create identity','manage managers'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.users')); ?></span>
                </a>

                <ul class="treeview-menu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view identity')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.identities.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.users')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create identity')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.identities.create')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('pages/users.create')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage managers')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.managers.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('pages/users.managers')); ?>

                            </a></li>
                        <li>
                            <a href="<?php echo e(route('accounting.managers.create')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('pages/users.create_manager')); ?>

                            </a></li>

                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage settings','manage branches','manage expenses'])): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fab fa-product-hunt"></i> <span> <?php echo e(__('sidebar.management')); ?>

                        <?php echo e(__('sidebar.settings')); ?></span>
                </a>

                <ul class="treeview-menu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage settings')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.settings.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.settings')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage branches')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.branches.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.branches')); ?>

                            </a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage expenses')): ?>
                        <li>
                            <a href="<?php echo e(route('accounting.expenses.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.expenses')); ?>

                            </a></li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>


    </ul>
</section>

<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/layout/head/sidebar.blade.php ENDPATH**/ ?>