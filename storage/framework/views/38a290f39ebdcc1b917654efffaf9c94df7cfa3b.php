<div class="table-responsive">
    <table class="table table-bordered text-center  table-striped">
        <thead class="">
        <tr>
            <th class=""><?php echo e(__('pages/invoice.id')); ?></th>
            <th class=""></th>
            <th class=""><?php echo e(__('pages/invoice.barcode')); ?></th>
            <th class="" width="20%"><?php echo e(__('pages/invoice.item_name')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.qty')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.price')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.total')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.discount')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.subtotal')); ?></th>

            <th class=""><?php echo e(__('pages/invoice.tax')); ?></th>
            <th class=""><?php echo e(__('pages/invoice.net')); ?></th>
        </tr>
        </thead>

        <tbody>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!(in_array($invoice->invoice_type,['sale','return_sale']) && $item->is_expense) && $item->item != null): ?>
                <tr <?php if($item->belong_to_kit==true): ?> class="bg-custom-primary" <?php endif; ?>>
                    <td>
                        <button class="btn btn-custom-primary btn-xs"><?php echo e($loop->index + 1); ?></button>

                    </td>
                    <td>
                        <?php if($item->item->is_need_serial): ?>
                            <button class="btn btn-custom-primary btn-xs" type="button" data-toggle="modal"
                                    data-target="#serialModal<?php echo e($item->id); ?>"><i class="fa fa-bars"></i></button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <span v-on="on"> <?php echo e($item->item->barcode); ?></span>

                            </template>
                            <span><?php echo e($item->cost); ?></span>
                        </v-tooltip>

                       </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <span v-on="on"> <?php echo e($item->item->locale_name); ?></span>

                            </template>
                            <span><?php echo e($item->item->warranty_title); ?></span>
                        </v-tooltip>
                    <td>
                        <input type="text" class="form-control input-sm amount-input" value="<?php echo e($item->qty); ?>"
                               disabled="">
                    </td>
                    <td>
                        <input type="text" class="form-control input-sm amount-input" value="<?php echo e(displayMoney($item->price)); ?>"
                               disabled="">

                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" value="<?php echo e(displayMoney($item->total)); ?>"
                               disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="discount"
                               value="<?php echo e(displayMoney($item->discount)); ?>"
                               disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="subtotal" readonly=""
                               value="<?php echo e(displayMoney($item->subtotal)); ?>" disabled="">
                    </td>






                    <td class="text-center">
                        <input type="text" class="form-control input-sm amount-input" placeholder="tax" readonly=""
                               value="<?php echo e(displayMoney($item->tax)); ?>" disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="net" readonly=""
                               value="<?php echo e(displayMoney($item->net)); ?>" disabled="">
                    </td>

                </tr>


                <?php if($item->item->is_need_serial): ?>
                    <div class="modal fade" id="serialModal<?php echo e($item->id); ?>" tabindex="-1" role="dialog"
                         aria-labelledby="serialModal<?php echo e($item->id); ?>"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="serialModal<?php echo e($item->id); ?>">السيريالات
                                        <?php echo e($item->item->locale_name); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-8 text-center">
                                            <label>السيريال</label>
                                        </div>
                                        <div class="col-md-4  text-center">
                                            <label>الحالة</label>
                                        </div>
                                    </div>
                                    <?php $__currentLoopData = $item->item->serials()->withoutGlobalScope("draft")
                                    ->where([
                                    ["sale_id",$invoice->id],
                                    ["item_id",$item->item->id],
                                    ])
                                    ->orWhere([["return_sale_id",$invoice->id],["item_id",$item->item->id]])
                                    ->orWhere([["return_purchase_id",$invoice->id],["item_id",$item->item->id]])
                                    ->orWhere([["purchase_id",$invoice->id],["item_id",$item->item->id]])
                                    ->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row">
                                            <div class="col-md-8  text-center">
                                                <?php echo e($serial->serial); ?>

                                            </div>
                                            <div class="col-md-4  text-center">
                                                
                                                <?php echo e(trans('pages/items.' . $serial->status)); ?>

                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        </tbody>
    </table>
</div>

<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/include/invoice/view_items.blade.php ENDPATH**/ ?>