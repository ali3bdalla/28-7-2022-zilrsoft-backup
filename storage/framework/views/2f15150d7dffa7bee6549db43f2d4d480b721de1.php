<?php if(!empty($invoice->payments)): ?>
    <div class="panel panel-primary">
        <div class="panel-heading  bg-custom-primary"><label><?php echo e(trans('pages/invoice.payments_methods')); ?></label></lable></div>
        <div class="panel-body">
            <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-group">
                    <div class="input-group"><span id="1" class="input-group-addon"
                                                   style="min-width: 130px; font-weight: bolder;
">
                    <?php echo e($payment->paymentable->locale_name); ?> &nbsp;
                    &nbsp; ( <a target="_blank" href="<?php echo e(route('accounting.payments.show',$payment->id)); ?>">عرض
                        السند</a> )</span>
                        <input aria-describedby="1" disabled="disabled" type="text"
                               class="form-control" value="<?php echo e($payment->amount); ?>" style="font-weight:
                                                   bolder;">
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/include/invoice/view_payments.blade.php ENDPATH**/ ?>