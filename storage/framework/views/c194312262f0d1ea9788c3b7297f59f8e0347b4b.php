<section class="sidebar" style=" margin-top: 50px;">
    <div class="user-panel">
        <img src="<?php echo e(asset(auth()->user()->organization->logo)); ?>" class="center-block img-responsive"
             alt="<?php echo e(auth()->user()->organization->title_ar); ?>" width="100px" style="">
        <h3 class="p-3 text-white btn btn-primary"><?php echo e(auth()->user()->organization->title_ar); ?></h3>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
        <li class="treeview">
            <a href="#">
                <i class="fa  fa-tachometer-alt"></i> <span><?php echo e(__('sidebar.dashboard')); ?></span>

            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="fa fa-tachometer-alt"></i>
                        <?php echo e(__('sidebar.dashboard')); ?></a></li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view accounting')): ?>
                    <li>
                        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fa fa-sun"></i>
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
                            <a href="<?php echo e(route('items.index')); ?>"><i class="fab fa-product-hunt"></i>
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
                            <a href="<?php echo e(route('purchases.index')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.purchases')); ?>

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
                            <a href="<?php echo e(route('sales.drafts.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.quotations')); ?>

                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('sales.drafts.create.service')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.services_quotations')); ?>

                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage inventory')): ?>
                        <li>
                            <a href="<?php echo e(route('inventory.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.beginning_inventory')); ?>

                            </a></li>

                        
                        
                        
                        

                    <?php endif; ?>
                    
                    
                    
                    
                    
                    
                    
                    

                    
                    
                    
                    

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
                            <a href="<?php echo e(route('vouchers.index')); ?>"><i class="fab fa-product-hunt"></i>
                                <?php echo e(__('sidebar.vouchers')); ?>

                            </a></li>


                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create voucher')): ?>
                        <li>
                            <a href="<?php echo e(route('vouchers.create')); ?>?voucher_type=payment"><i class="fa
                            fa-sun"></i>
                                <?php echo e(__('pages/vouchers.create_payment')); ?>

                            </a></li>

                        <li>
                            <a href="<?php echo e(route('vouchers.create')); ?>?voucher_type=receipt"><i class="fa
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
                            <a href="<?php echo e(route('accounts.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.chart_of_accounts')); ?>

                            </a></li>

                        <li>
                            <a href="<?php echo e(route('financial_statements.trial_balance')); ?>"><i class="fa fa-sun"></i>
                                ميزان المراجعة
                            </a></li>
                        
                        
                        
                        
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view transactions')): ?>
                        <li>
                            <a href="<?php echo e(route('entities.index')); ?>"><i class="fa fa-sun"></i>
                                <?php echo e(__('sidebar.transactions')); ?>

                            </a></li>
                    <?php endif; ?>

                    <li><a href="<?php echo e(route('accounts.reports.index')); ?>">
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