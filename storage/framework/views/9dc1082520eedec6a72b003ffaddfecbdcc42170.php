<?php $__env->startSection('page_css'); ?>
    <style>
        .navbar {
            background-color: green !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title',__('pages/invoice.view') . ' | '. $invoice->invoice_number ); ?>
<?php $__env->startSection('buttons'); ?>

    <a href="<?php echo e(route('accounting.printer.a4',$invoice->id)); ?>" target="_blank" class="btn btn-default">
        <i class="fa fa-print"></i> <?php echo e(__('pages/invoice.price_a4')); ?>

    </a>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create purchase')): ?>
        <a href="<?php echo e(route('purchases.create')); ?>" class="btn btn-default"><i class="fa fa-plus-square"></i> <?php echo e(trans
        ('pages/invoice.create')); ?></a>
    <?php endif; ?>


    <?php if($invoice->invoice_type=='pending_purchase'): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('confirm purchase')): ?>
            <a href="<?php echo e(route('accounting.purchases.clone',$invoice->id)); ?>" class="btn btn-default"><i class="fa
            fa-plus-square"></i>تاكيد الفاتورة</a>
        <?php endif; ?>

    <?php endif; ?>


    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("edit purchase")): ?>
        <?php if($invoice->is_deleted==1): ?>
            <a href="<?php echo e(route('accounting.purchases.edit',$invoice->id)); ?>" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.return')); ?>

            </a>
        <?php endif; ?>
        <?php if($invoice->is_updated==1): ?>
            <a href="<?php echo e(route('accounting.purchases.destroy',$invoice->id)); ?>" class="btn btn-danger">
                <i class="fa fa-trash"></i> <?php echo e(__('pages/invoice.delete')); ?>

            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/invoice.vendor')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($invoice->purchase ?
                               $invoice->purchase->vendor->locale_name : null); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list"
                              class="input-group-addon"><?php echo e(trans('pages/invoice.vendor_invoice_id')); ?></span>
                        <input type="text" disabled="disabled"
                               value="<?php echo e($invoice->purchase ? $invoice->purchase->vendor_invoice_id : ""); ?>"
                               class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
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
                        <span id="vendors-list" class="input-group-addon"><?php echo e(trans('pages/invoice.receiver')); ?></span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="<?php echo e($invoice->purchase ?
                               $invoice->purchase->receiver->locale_name : ""); ?>">
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
                 'items' => $invoice->items()->withoutGlobalScope('draftScope')->get()
            ])) echo $__env->make('accounting.include.invoice.view_items',[
                 'items' => $invoice->items()->withoutGlobalScope('draftScope')->get()
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
                            <accounting-barcode-bulk-printer-layout-component
                                    :invoice-id='<?php echo json_encode($invoice->title, 15, 512) ?>'
                                    :items='<?php echo json_encode($invoice->items->load('item'), 15, 512) ?>'
                            >
                            </accounting-barcode-bulk-printer-layout-component>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/zilrsoft.com/resources/views/accounting/purchases/show.blade.php ENDPATH**/ ?>