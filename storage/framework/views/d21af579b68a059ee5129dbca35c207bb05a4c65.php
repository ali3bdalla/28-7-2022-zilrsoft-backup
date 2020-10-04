<?php if($payment->payment_type=='receipt'): ?>
    <?php $__env->startSection('title',__('pages/vouchers.show_receipt')); ?>
<?php else: ?>
    <?php $__env->startSection('title',__('pages/vouchers.show_payment')); ?>
<?php endif; ?>


<?php $__env->startSection('buttons'); ?>
    <a target="_blank" href="<?php echo e(route('accounting.printer.voucher',$payment->id)); ?>" class="btn btn-custom-primary"><i
                class="fa
    fa-print"></i> <?php echo e(__('reusable.print')); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-6 text-center">
            <h5><?php echo e(__('pages/vouchers.amount')); ?> : <span
                        style="font-weight: bolder"><?php echo e($payment->amount); ?> ريال</span></h5>
        </div>
        <div class="col-md-6 text-center">
            <h5> <?php echo e($payment->amount_ar_words); ?> </h5>
        </div>

    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__('pages/vouchers.type')); ?>

                                </span>
                    <input placeholder="<?php echo e(__('pages/vouchers.type')); ?>" disabled
                           value="<?php if($payment->account_type=="App\Models\Invoice"): ?><?php echo e(__('pages/vouchers.invoices_payment')); ?><?php else: ?><?php echo e(__('pages/vouchers.advance_payment')); ?><?php endif; ?>"
                           type="text" class="form-control">
                </div>

            </div>
        </div>


        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                ('pages/vouchers.client')); ?>

                                </span>
                    <input placeholder="<?php echo e(__('pages/vouchers.client')); ?>" disabled
                           value="<?php echo e($payment->user->locale_name); ?>"
                           type="text" class="form-control">
                </div>

            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                ('pages/vouchers.gateway')); ?>

                                </span>
                    <input placeholder="<?php echo e(__('pages/vouchers.gateway')); ?>" disabled
                           value="<?php echo e($payment->account->locale_name); ?>"
                           type="text" class="form-control">

                    
                    
                    
                </div>

            </div>
        </div>

        <?php if($payment->slug=='transfer' && $payment->payment_type=='payment'): ?>
            <div class="col-md-6">
                <div class="filter_area">
                    <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                ('pages/vouchers.user_account')); ?>

                                </span>
                        <input placeholder="<?php echo e(__('pages/vouchers.gateway')); ?>" disabled
                               value="<?php echo e($payment->user_gateway->locale_name); ?>"
                               type="text" class="form-control">
                    </div>

                </div>
            </div>
        <?php endif; ?>

    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                    ('reusable.creator')); ?>

                                    </span>
                    <input placeholder="<?php echo e(__('reusable.creator')); ?>" disabled
                           value="<?php echo e($payment->creator->locale_name); ?>"
                           type="text" class="form-control">
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                    ('reusable.date')); ?>

                                    </span>
                    <input placeholder="<?php echo e(__('reusable.date')); ?>" disabled
                           value="<?php echo e($payment->created_at); ?>"
                           type="text" class="form-control">
                </div>

            </div>
        </div>
    </div>

    <?php if($payment->description!=''): ?>
        <div class="row">
            <div class="col-md-12">
                <h5><?php echo e(__('pages/vouchers.payment_description')); ?>

                    : </h5>

            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <label>
                    <textarea class="form-control" disabled><?php echo e($payment->description); ?></textarea>
                </label>
            </div>
        </div>


    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/zilrsoft.com/resources/views/accounting/vouchers/show.blade.php ENDPATH**/ ?>