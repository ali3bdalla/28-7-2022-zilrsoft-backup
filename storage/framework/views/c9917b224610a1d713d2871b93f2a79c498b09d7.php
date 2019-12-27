<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view transactions')): ?>
    <div class="panel panel-info">
        <div class="panel-heading  bg-custom-primary">
            <label><?php echo e(trans('sidebar.transactions')); ?></label>
        </div>
        <table class="table table-bordered table-hover text-center">
            <thead>
            <th><?php echo e(trans('pages/transactions.date')); ?></th>
            <th><?php echo e(trans('pages/transactions.account_name')); ?></th>
            <th><?php echo e(trans('pages/transactions.debit')); ?></th>
            <th><?php echo e(trans('pages/transactions.credit')); ?></th>
            </thead>


            <?php if($invoice->invoice_type=='sale'): ?>
                <?php if ($__env->exists('accounting.include.invoice.transactions.sale')) echo $__env->make('accounting.include.invoice.transactions.sale', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($invoice->invoice_type=='r_sale'): ?>
                <?php if ($__env->exists('accounting.include.invoice.transactions.return_sale')) echo $__env->make('accounting.include.invoice.transactions.return_sale', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($invoice->invoice_type=='r_purchase'): ?>
                <?php if ($__env->exists('accounting.include.invoice.transactions.return_purchase')) echo $__env->make('accounting.include.invoice.transactions.return_purchase', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php if ($__env->exists('accounting.include.invoice.transactions.purchase')) echo $__env->make('accounting.include.invoice.transactions.purchase', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

        </table>
    </div>
<?php endif; ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/include/invoice/view_transactions.blade.php ENDPATH**/ ?>