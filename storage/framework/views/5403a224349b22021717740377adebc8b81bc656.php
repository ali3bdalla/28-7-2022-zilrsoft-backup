<?php $__env->startSection('title',__('sidebar.barcode')); ?>
<?php $__env->startSection('content'); ?>
    <div class="panel">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="panel-body">
                <accounting-barcode-printer-layout-component
                        :item='<?php echo json_encode($item, 15, 512) ?>'
                >
                </accounting-barcode-printer-layout-component>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/zilrsoft.com/resources/views/accounting/items/barcode/show.blade.php ENDPATH**/ ?>