<?php if($payment->payment_type=='receipt'): ?>
    <?php $__env->startSection('title',__('pages/payments.show_receipt')); ?>
<?php else: ?>
    <?php $__env->startSection('title',__('pages/payments.show_payment')); ?>
<?php endif; ?>
<?php $__env->startSection('desctipion',__('pages/payments.show')); ?>
<?php $__env->startSection('route',route('management.payments.index')); ?>


<?php $__env->startSection('translator'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="box">
            <div class="columns">
                <div class="column text-center">
                    <h5><?php echo e(__('pages/payments.amount')); ?> : <?php echo e($payment->amount); ?> ريال</h5>
                </div>
                <div class="column text-center">
                    <h5> <?php echo e($payment->amount_ar_words); ?> </h5>
                </div>
                <div class="column text-left">
                    <a target="_blank" href="<?php echo e(route('management.printers.voucher',$payment->id)); ?>" class="button
                    is-info"><i
                                class="fa fa-print"></i>
                        &nbsp;
                        &nbsp;
                        <?php echo e(__('reusable.print')); ?></a>
                </div>
            </div>
        </div>


        <div class="box">
            <div class="columns">
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__('pages/payments.type')); ?>

                                </span>
                            <input placeholder="<?php echo e(__('pages/payments.type')); ?>" disabled
                                   value="<?php if($payment->paymentable_type=="App\Invoice"): ?><?php echo e(__('pages/payments.invoices_payment')); ?><?php else: ?><?php echo e(__('pages/payments.advance_payment')); ?><?php endif; ?>"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>


                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                ('pages/payments.client')); ?>

                                </span>
                            <input placeholder="<?php echo e(__('pages/payments.client')); ?>" disabled
                                   value="<?php echo e($payment->user->name); ?>"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>

            </div>


            <div class="columns">
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                ('pages/payments.gateway')); ?>

                                </span>
                            <input placeholder="<?php echo e(__('pages/payments.gateway')); ?>" disabled
                                   value="<?php if($payment->paymentable->parent_id>=1): ?><?php echo e($payment->paymentable->parent->locale_name); ?> - <?php endif; ?><?php echo e($payment->paymentable->locale_name); ?>"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>

                <?php if($payment->slug=='transfer' && $payment->payment_type=='payment'): ?>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                ('pages/payments.user_account')); ?>

                                </span>
                                <input placeholder="<?php echo e(__('pages/payments.gateway')); ?>" disabled
                                       value="<?php echo e($payment->user_gateway->locale_name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

            </div>


            <div class="columns">
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                    ('reusable.creator')); ?>

                                    </span>
                            <input placeholder="<?php echo e(__('reusable.creator')); ?>" disabled
                                   value="<?php echo e($payment->creator->name); ?>"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>
                <div class="column">
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
                        <h5><?php echo e(__('pages/payments.payment_description')); ?>

                            : </h5>

                    </div>
                </div>


                <div class="columns">
                    <div class="column">
                        <textarea class="form-control" disabled><?php echo e($payment->description); ?></textarea>
                    </div>
                </div>


            <?php endif; ?>


        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/payments/show.blade.php ENDPATH**/ ?>