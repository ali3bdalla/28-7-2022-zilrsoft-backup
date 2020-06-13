<?php $__env->startSection('title',__('sidebar.dashboard')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check(' view charts')): ?>
        <div class="row">
            <div class="col-md-4">
                <accounting-dashboard-active-items-layout-component>

                </accounting-dashboard-active-items-layout-component>

            </div>
            <div class="col-md-4">
                <accounting-dashboard-items-chart-component
                        type="bar"
                        title="منتجات">

                </accounting-dashboard-items-chart-component>
            </div>
            <div class="col-md-4">
                <accounting-dashboard-items-chart-component
                        type="radar"
                        title="فلاتر">

                </accounting-dashboard-items-chart-component>
            </div>
        </div>
        
        
        
        
        

        
        
        
        
        
        

        
        
        
        
        
        

        
        
        
    <?php endif; ?>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/dashboard/index.blade.php ENDPATH**/ ?>