<?php $__env->startSection('title',__('pages/invoice.view') . ' | '. $invoice->invoice_number ); ?>



<?php $__env->startSection('page_css'); ?>
    <?php if($invoice->is_draft): ?>
        <style>
            .navbar {
                background-color: #b8b83a !important;
            }
        </style>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('buttons'); ?>
    <?php if(auth()->user()->id != 19): ?>

        <a href="<?php echo e(route('accounting.printer.a4',$invoice->id)); ?>" target="_blank" class="btn btn-default">
            <i class="fa fa-print"></i> <?php echo e(__('pages/invoice.price_a4')); ?>

        </a>

        <accounting-print-receipt-layout-component
                :invoice-id="<?php echo e($invoice->id); ?>"></accounting-print-receipt-layout-component>

        <?php if($invoice->is_draft): ?>
            <a href="<?php echo e(route('sales.drafts.clone',$invoice->id)); ?>" class="btn btn-default">
                <i class="fa fa-copy"></i> <?php echo e(__('pages/invoice.quotation_to_sale')); ?>

            </a>
        <?php else: ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create sale')): ?>
                <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-default"><i class="fa fa-plus-square"></i> <?php echo e(trans
        ('pages/invoice.create')); ?></a>
            <?php endif; ?>
            <?php if($invoice->invoice_type=='sale'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("edit sale")): ?>
                    <?php if($invoice->is_deleted==0): ?>
                        <a href="<?php echo e(route('sales.edit',$invoice->id)); ?>" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.return')); ?>

                        </a>
                    <?php endif; ?>
                    
                    
                    
                    
                    
                <?php endif; ?>
            <?php endif; ?>
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
                               class="form-control" value="<?php echo e($invoice->sale->alice_name=="" ?$invoice->sale->client->locale_name:
                               $invoice->sale->alice_name); ?>">
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
                 'items' => $invoice->items()->withoutGlobalScope('draft')->get()
            ])) echo $__env->make('accounting.include.invoice.view_items',[
                 'items' => $invoice->items()->withoutGlobalScope('draft')->get()
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-3">
                    <?php if ($__env->exists('accounting.include.invoice.view_amounts')) echo $__env->make('accounting.include.invoice.view_amounts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <?php if($invoice->invoice_type!='quotation'): ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/sales/view.blade.php ENDPATH**/ ?>