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
                                   value="<?php if($payment->paymentable_type=="App\Invoice"): ?><?php echo e(__('pages/payments.invoices_payment')); ?><?php else: ?><?php echo e(__
                                   ('pages/payments.advance_payment')); ?><?php endif; ?>"
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
                                   value="<?php echo e($payment->account->locale_name); ?>"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>


                <?php if(in_array($payment->gateway_id,[2])): ?>
            </div>
            <?php if($payment->payment_type=='receipt'): ?>
                <div class="columns">
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                            <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                            ('pages/payments.user_bank'). ' '.__('pages/payments.transfer_from')); ?>

                                            </span>
                                <input placeholder="<?php echo e(__
                                            ('pages/payments.user_bank'). ' '.__('pages/payments.transfer_from')); ?>"
                                       disabled
                                       value="<?php echo e($payment->user_account->bank->name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                            <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                            ('pages/payments.account')); ?>

                                            </span>
                                <input placeholder="<?php echo e(__('pages/payments.account')); ?>" disabled
                                       value="<?php echo e($payment->user_account->account); ?>"
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
                                            ('pages/payments.organization_bank'). ' '.__('pages/payments.transfer_to')); ?>

                                            </span>
                                <input placeholder="<?php echo e(__
                                            ('pages/payments.organization_bank'). ' '.__('pages/payments.transfer_to')); ?>"
                                       disabled
                                       value="<?php echo e($payment->organization_account->bank->name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                            <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                            ('pages/payments.account')); ?>

                                            </span>
                                <input placeholder="<?php echo e(__('pages/payments.account')); ?>" disabled
                                       value="<?php echo e($payment->organization_account->account); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>


            <?php else: ?>


                <div class="columns">
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                        <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                        ('pages/payments.gateway')); ?>

                                        </span>
                                <input placeholder="<?php echo e(__('pages/payments.gateway')); ?>" disabled
                                       value="<?php echo e($payment->gateway->name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                        <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                        ('pages/payments.gateway')); ?>

                                        </span>
                                <input placeholder="<?php echo e(__('pages/payments.gateway')); ?>" disabled
                                       value="<?php echo e($payment->gateway->name); ?>"
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
                                       value="<?php echo e($payment->gateway->name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                        <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                        ('pages/payments.gateway')); ?>

                                        </span>
                                <input placeholder="<?php echo e(__('pages/payments.gateway')); ?>" disabled
                                       value="<?php echo e($payment->gateway->name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <div class="columns">
                <?php endif; ?>

                <?php if(in_array($payment->gateway_id,[5])): ?>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__('pages/payments.bank')); ?>

                                    </span>
                                <input placeholder="<?php echo e(__('pages/payments.bank')); ?>" disabled
                                       value="<?php echo e($payment->bank->name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
            </div>
            <div class="columns">
                <?php endif; ?>

                <?php if(in_array($payment->gateway_id,[4,5])): ?>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__('pages/payments.reference_number')); ?>

                                </span>
                                <input placeholder="<?php echo e(__('pages/payments.reference_number')); ?>" disabled
                                       value="<?php echo e($payment->account); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>

                <?php endif; ?>

            </div>

            <?php if(in_array($payment->gateway_id,[6])): ?>
                <div class="columns">
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                    ('pages/payments.email')); ?>

                                    </span>
                                <input placeholder="<?php echo e(__('pages/payments.email')); ?>" disabled
                                       value="<?php echo e($payment->account); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white"><?php echo e(__
                                    ('pages/payments.reference_number')); ?>

                                    </span>
                                <input placeholder="<?php echo e(__('pages/payments.reference_number')); ?>" disabled
                                       value="<?php echo e($payment->account_name); ?>"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>

                </div>
            <?php endif; ?>


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


        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/payments/show.blade.php ENDPATH**/ ?>