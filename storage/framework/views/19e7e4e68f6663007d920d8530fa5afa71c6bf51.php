<?php $__env->startSection('title',__('pages/invoice.view') . ' | '. $invoice->title ); ?>
<?php $__env->startSection('buttons'); ?>
    <a href="<?php echo e(route('accounting.sales.print',$invoice->id)); ?>" class="btn btn-default">
        <i class="fa fa-print"></i> <?php echo e(__('pages/invoice.price_a4')); ?>

    </a>
    <accounting-print-receipt-layout-component
            :invoice-id="<?php echo e($invoice->id); ?>"></accounting-print-receipt-layout-component>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("edit sale")): ?>
        <?php if($invoice->is_deleted==1): ?>
            <a href="<?php echo e(route('accounting.sales.edit',$invoice->id)); ?>" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.return')); ?>

            </a>
        <?php endif; ?>
        <?php if($invoice->is_updated==1): ?>
            <a href="<?php echo e(route('accounting.sales.destroy',$invoice->id)); ?>" class="btn btn-danger">
                <i class="fa fa-trash"></i> <?php echo e(__('pages/invoice.delete')); ?>

            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/invoice.client')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($invoice->sale->client->locale_name); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/invoice.created_at')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               value="<?php echo e($invoice->created_at); ?>"
                               class="form-control date_field_center">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/invoice.salesman')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($invoice->sale->salesman->locale_name); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/invoice.department')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($invoice->department->locale_title); ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <?php if ($__env->exists('accounting.include.invoice.view_items',[
                 'items' => $invoice->items
            ])) echo $__env->make('accounting.include.invoice.view_items',[
                 'items' => $invoice->items
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
                            <?php if ($__env->exists('accounting.include.invoice.view_payments')) echo $__env->make('accounting.include.invoice.view_payments', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($__env->exists('accounting.include.invoice.view_transactions')) echo $__env->make('accounting.include.invoice.view_transactions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/sales/show.blade.php ENDPATH**/ ?>