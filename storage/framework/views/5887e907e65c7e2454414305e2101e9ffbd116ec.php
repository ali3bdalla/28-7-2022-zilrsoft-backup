<?php $__env->startSection('title',$kit->locale_name); ?>

<?php $__env->startSection('content'); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/items.name_ar')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($kit->ar_name); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/items.name_en')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($kit->name); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/items.barcode')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($kit->barcode); ?>">
                    </div>
                </div>

            </div>

        </div>
        <div class="panel-body">
            <?php if ($__env->exists('accounting.include.invoice.view_items',[
                 'items' => $kit->items
            ])) echo $__env->make('accounting.include.invoice.view_items',[
                 'items' => $kit->items
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-3">
                    <?php if ($__env->exists('accounting.include.invoice.view_amounts')) echo $__env->make('accounting.include.invoice.view_amounts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="col-md-9">
                    <div class="row">

                        <div class="col-md-12">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/kits/show.blade.php ENDPATH**/ ?>