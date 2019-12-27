<?php if(in_array($invoice->invoice_type,['sale','r_sale'])): ?>

<?php else: ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            تكاليف اضافية
        </div>
        <div class="panel-body">

            <table class="table table-bordered">
                <?php $__currentLoopData = $invoice->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th text="item.name"
                            style="text-align: right !important;"><?php echo e($expense->expense->locale_name); ?> - <?php if($expense->with_net): ?>
                                <span class="badge badge-primary">مضمنة</span>


                            <?php else: ?>
                                <span class="badge badge-info">مستقلة</span>
                            <?php endif; ?>
                        </th>

                        <th class="has-text-white">
                            <input type="text" class="form-control input-xs amount-input" value="<?php echo e($expense->amount); ?>"
                                   disabled="">

                        </th>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>

<?php endif; ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/include/invoice/view_expenses.blade.php ENDPATH**/ ?>