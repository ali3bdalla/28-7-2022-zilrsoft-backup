<?php $__env->startSection('title',__('sidebar.dashboard')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <div class="row">
        <div class="col-md-4">

            <accounting-dashboard-items-chart-component
                    type="line"
                    title="عمليات">

            </accounting-dashboard-items-chart-component>
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
    <div class="row">
        <div class="col-md-4">
            <accounting-dashboard-items-chart-component
                    type="polarArea"
                    title="عمليات">

            </accounting-dashboard-items-chart-component>
        </div>
        <div class="col-md-4">
            <accounting-dashboard-items-chart-component
                    type="doughnut"
                    title="منتجات">

            </accounting-dashboard-items-chart-component>
        </div>
        <div class="col-md-4">
            <accounting-dashboard-items-chart-component
                    type="pie"
                    title="فلاتر">

            </accounting-dashboard-items-chart-component>
        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/dashboard/show.blade.php ENDPATH**/ ?>