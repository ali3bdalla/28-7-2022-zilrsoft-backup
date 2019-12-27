<div class="table-responsive">
    <table class="table table-bordered text-center  table-striped">
        <thead class="">
        <tr>
            <th class=""><?php echo e(__('pages/invoice.id')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.barcode')); ?></th>
            <th class="" width="20%"><?php echo e(__('pages/invoice.item_name')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.qty')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.price')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.total')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.discount')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.subtotal')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.vat')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.tax')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.net')); ?></th>
        </tr>
        </thead>

        <tbody>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!(in_array($invoice->invoice_type,['sale','r_sale']) && $item->is_expense)): ?>
                <tr <?php if($item->belong_to_kit==true): ?> class="bg-custom-primary" <?php endif; ?>>
                    <td>
                        <button class="btn btn-custom-primary btn-xs"><?php echo e($loop->index + 1); ?></button>
                    </td>
                    <td><?php echo e($item->item->barcode); ?></td>
                    <td><?php echo e($item->item->locale_name); ?></td>
                    <td>
                        <input type="text" class="form-control input-sm amount-input" value="<?php echo e($item->qty); ?>"
                               disabled="">
                    </td>
                    <td>
                        <input type="text" class="form-control input-sm amount-input" value="<?php echo e($item->price); ?>"
                               disabled="">

                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" value="<?php echo e($item->total); ?>"
                               disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="discount"
                               value="<?php echo e($item->discount); ?>"
                               disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="subtotal" readonly=""
                               value="<?php echo e($item->subtotal); ?>" disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="vat purchase"
                               readonly=""
                               value="<?php echo e($item->item->vtp); ?>%" disabled="">
                    </td>
                    <td class="text-center">
                        <input type="text" class="form-control input-sm amount-input" placeholder="tax" readonly=""
                               value="<?php echo e($item->tax); ?>" disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="net" readonly=""
                               value="<?php echo e($item->net); ?>" disabled="">
                    </td>

                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        </tbody>
    </table>
</div>

<?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/include/invoice/view_items.blade.php ENDPATH**/ ?>