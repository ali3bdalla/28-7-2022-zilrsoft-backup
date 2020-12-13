<div class="panel">
    <div class="panel-header">
        <?php echo e(__('pages/invoice.invoice_data')); ?>

    </div>
    <div class="panel-body text-center">
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label><?php echo e(__('pages/invoice.total')); ?></label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="<?php echo e(displayMoney($invoice->total)); ?>"
                           disabled="">
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label><?php echo e(__('pages/invoice.discount')); ?></label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="<?php echo e(displayMoney($invoice->discount)); ?>"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label><?php echo e(__('pages/invoice.subtotal')); ?></label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="<?php echo e(displayMoney($invoice->subtotal)); ?>"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label><?php echo e(__('pages/invoice.tax')); ?></label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="<?php echo e(displayMoney($invoice->tax)); ?>"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label><?php echo e(__('pages/invoice.net')); ?></label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="<?php echo e(displayMoney($invoice->net)); ?>"
                           disabled="">
                </div>
            </div>
        </div>
        
        

    </div>
    <div class="col-md-12">
        <?php if(!in_array($invoice->invoice_type,['sale','return_sale'])): ?>
            <?php if ($__env->exists('accounting.include.invoice.view_expenses')) echo $__env->make('accounting.include.invoice.view_expenses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
</div><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/accounting/include/invoice/view_amounts.blade.php ENDPATH**/ ?>