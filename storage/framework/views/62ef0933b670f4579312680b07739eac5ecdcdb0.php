<div class="panel panel-primary">
    <div class="panel-heading">
        تكاليف اضافية
    </div>

    <?php if(in_array($invoice->invoice_type,['sale','r_sale'])): ?>
        <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($item->item->is_expense): ?>
                <tr>

                    <!-- <th class="has-text-white"></th> -->

                    <th text="item.name"
                        style="text-align: right !important;"><?php echo e($item->item->locale_name); ?></th>

                    <th class="has-text-white">
                        <input type="text" class="input" value="<?php echo e($item->price); ?>"
                               disabled="">

                    </th>

                    <th class="has-text-white">
                        <input type="text" class="input" placeholder="tax" readonly=""
                               value="<?php echo e($item->tax); ?>" disabled="">
                    </th>
                    <th class="has-text-white">
                        <input type="text" class="input" placeholder="net" readonly=""
                               value="<?php echo e($item->net); ?>" disabled="">
                    </th>

                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php elseif(in_array($invoice->invoice_type,['purchase','beginning_inventory','r_purchase'])): ?>


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
    <?php endif; ?>

</div>
<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/include/invoice/view_expenses.blade.php ENDPATH**/ ?>