<?php $__env->startSection('title',__('pages/invoice.purchase')); ?>
<?php $__env->startSection('desctipion',__('pages/invoice.view_purchase')); ?>
<?php $__env->startSection('route',route('management.purchases.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="message">


        <div class="message-header has-background-dark">
            <h4 class="title has-text-white pull-right"><?php echo e(__('pages/invoice.invoice_number')); ?> <?php echo e($purchase->prefix . $purchase->invoice_id); ?></h4>
            <div class="columns">
                <div class="column">
                    <print-invoice-component :invoice_id="<?php echo e($purchase->invoice_id); ?>"
                                             :title='<?php echo json_encode( __('pages/invoice.price_invoice'), 15, 512) ?>'></print-invoice-component>
                    <?php if($purchase->invoice_type=='purchase'): ?>
                        
                        
                        
                        <a href="<?php echo e(route('management.purchases.edit',$purchase->id)); ?>" class="button is-warning"><i
                                    class="fa
                        fa-redo"></i>&nbsp; <?php echo e(__('pages/invoice.return')); ?></a>&nbsp;
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <div class="message-body">
            <div class="has-background-light">
                <div class="columns">
                    <div class="column">
                        <!-- <label>client name</label> -->
                        <div class="">
                            <div class="input-group">
                                <span class="input-group-addon"
                                      id="vendors-list"><?php echo e(__('pages/invoice.vendor')); ?></span>

                                <input type="text" name="" aria-describedby="time-field" class="form-control"
                                       disabled="" value="<?php echo e($purchase->vendor->name); ?>">

                            </div>


                        </div>
                    </div>
                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon"
                                  id="vendor-in-number"><?php echo e(__('pages/invoice.vendor_inc_number')); ?></span>
                            <input type="text" name="" aria-describedby="vendor-in-number" class="form-control"
                                   disabled="" value="<?php echo e($purchase->vendor_inc_number); ?>">

                        </div>


                    </div>

                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field"><?php echo e(__('pages/invoice.date')); ?></span>
                            <input type="text" name="" aria-describedby="time-field" style="    direction: ltr
                            !important;" class="form-control" disabled=""
                                   value="<?php echo e($purchase->created_at); ?>">

                        </div>


                    </div>


                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>client name</label> -->
                            <div class="">
                                <div class="input-group">
                                    <span class="input-group-addon" id="receivers-list"><?php echo e(__('pages/invoice.receiver')); ?></span>
                                    <input type="text" name="" aria-describedby="receivers-list" class="form-control"
                                           disabled="" value="<?php echo e($purchase->receiver->name); ?>">

                                </div>

                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department-field"><?php echo e(__('pages/invoice.department')); ?></span>
                                <input type="text" name="" aria-describedby="department-field" readonly
                                       class="form-control" disabled=""
                                       value="<?php echo e($purchase->invoice->branch->name); ?>">

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <hr>
            <div class="table-container">
                <table class="table is-bordered text-center is-fullwidth is-narrow is-hoverable is-striped">
                    <thead class="has-background-dark ">
                    <tr>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.id')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.barcode')); ?></th>
                        <th class="has-text-white" width="20%"><?php echo e(__('pages/invoice.item_name')); ?></th>
                        <th class="has-text-white" width="3%"><?php echo e(__('pages/invoice.qty')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.price')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.total')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.discount')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.subtotal')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.vat')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.tax')); ?></th>
                        <th class="has-text-white"><?php echo e(__('pages/invoice.net')); ?></th>
                    </tr>
                    </thead>

                    <tbody>


                    <?php $__currentLoopData = $purchase->invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->item->is_expense || $purchase->invoice->parent_invoice_id<=0): ?>
                            <tr>
                                <th class="has-text-white">
                                    <button class="button is-info is-small"><?php echo e($loop->index + 1); ?></button>
                                </th>
                                <!-- <th class="has-text-white"></th> -->
                                <th text="item.barcode"><?php echo e($item->item->barcode); ?></th>
                                <th text="item.name"><?php echo e($item->item->locale_name); ?></th>
                                <th width="6%">
                                    <input type="text" class="input" value="<?php echo e($item->qty); ?>" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" value="<?php echo e($item->price); ?>" disabled="">

                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" value="<?php echo e($item->total); ?>" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="discount"
                                           value="<?php echo e($item->discount); ?>"
                                           disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="subtotal" readonly="" value="<?php echo e($item->subtotal); ?>" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="vat purchase" readonly="" value="<?php echo e($item->item->vtp); ?>%" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="tax" readonly="" value="<?php echo e($item->tax); ?>" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="net" readonly="" value="<?php echo e($item->net); ?>" disabled="">
                                </th>

                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div class="columns">


                    <div data-v-73cd913d="" class="column is-three-quarters">
                        <div data-v-73cd913d="">


                            <div class="panel panel-primary">

                                <div class="panel-body">
                                    <div class="panel-heading">
                                        تكاليف اضافية
                                    </div>
                                    <table class="table table-bordered">
                                        <?php $__currentLoopData = $purchase->invoice->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>

                                                <!-- <th class="has-text-white"></th> -->

                                                <th text="item.name"
                                                    style="text-align: right !important;"><?php echo e($expense->expense->locale_name); ?></th>

                                                <th class="has-text-white">
                                                    <input type="text" class="input" value="<?php echo e($expense->amount); ?>"
                                                           disabled="">

                                                </th>


                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </table>
                                </div>
                            </div>


                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <?php if(!empty($purchase->invoice->payments)): ?>
                                        <?php $__currentLoopData = $purchase->invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-group">
                                                <div class="input-group"><span id="1" class="input-group-addon"
                                                                               style="min-width: 130px; font-weight: bolder;
"><?php echo e($payment->account->locale_name); ?> &nbsp;&nbsp; ( <a target="_blank" href="<?php echo e(route('management.payments.show',
$payment->id)); ?>">عرض السند</a> )</span>
                                                    <input aria-describedby="1" disabled="disabled" type="text"
                                                           class="form-control" value="<?php echo e($payment->amount); ?>" style="font-weight:
                                                   bolder;">
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                </div>


                                <div class="padding:30px">
                                    <div class="columns text-center ">
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">الاجمالي</span>
                                                    <h1 class="title text-center"><?php echo e($purchase->invoice->net); ?></h1></div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المدفوع</span>
                                                    <h1 class="title text-center"><?php echo e(money_format("%i",
                                                $purchase->invoice->net
                                                                                      - $purchase->invoice->remaining)); ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المتبقي / آجل</span>
                                                    <h1 class="title text-center"><input disabled="disabled"
                                                                                         value="<?php echo e($purchase->invoice->remaining); ?>"
                                                                                         readonly="readonly" type="text"
                                                                                         class="form-control is-danger has-error onlyhidden"
                                                                                         style="font-size: 32px; height: 36px;">
                                                        <input readonly="readonly" disabled="disabled" type="text"
                                                               class="form-control is-danger has-error onlyhidden"
                                                               style="font-size: 32px; height: 36px; display: none;">
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-footer">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead>
                                        <th>التاريخ</th>
                                        <th>اسم الحساب</th>
                                        <th>مدين</th>
                                        <th>دائن</th>
                                        </thead>
                                        <tbody class="text-center">
								<?php $total_debit = 0; $total_credit = 0;?>
                                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($purchase->invoice_type=='r_purchase'): ?>
                                                <?php if($transaction['description']=='to_item' ||
                                            $transaction['description']=='to_tax'): ?>
										   <?php $total_credit = $total_credit + $transaction['amount']?>

                                                     <tr>
                                                         <td class="datedirection"><?php echo e($transaction->created_at); ?></td>
                                                         <td><?php echo e($transaction->creditable->locale_name); ?></td>
                                                         <td></td>
                                                         <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
                                                     </tr>
                                                <?php else: ?>

										   <?php $total_debit = $total_debit + $transaction['amount']?>
                                                     <tr>
                                                         <td class="datedirection"><?php echo e($transaction->created_at); ?></td>
                                                         <td><?php echo e($transaction->debitable->locale_name); ?></td>
                                                         <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
                                                         <td></td>
                                                     </tr>
                                                <?php endif; ?>




                                            <?php else: ?>





                                                <?php if($transaction['description']=='to_item' || $transaction['description']=='to_tax'): ?>

										   <?php $total_debit = $total_debit + $transaction['amount']?>
                                                     <tr>
                                                         <td class="datedirection"><?php echo e($transaction->created_at); ?></td>
                                                         <td><?php echo e($transaction->debitable->locale_name); ?></td>
                                                         <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
                                                         <td></td>
                                                     </tr>

                                                <?php else: ?>


										   <?php $total_credit = $total_credit + $transaction['amount']?>
                                                     <tr>
                                                         <td class="datedirection"><?php echo e($transaction->created_at); ?></td>
                                                         <td><?php echo e($transaction->creditable->locale_name); ?></td>
                                                         <td></td>
                                                         <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
                                                     </tr>
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <thead>
                                        <th>المجموع</th>
                                        <th></th>
                                        <th><?php echo e(money_format("%i",$total_debit)); ?></th>
                                        <th><?php echo e(money_format("%i",$total_credit)); ?></th>
                                        </thead>
                                    </table>
                                </div>


                            </div>

                        </div>

                    </div>
                    <div class="column">
                        <div class="card">
                            <div class="message-header">
                                <?php echo e(__('pages/invoice.invoice_data')); ?>

                            </div>
                            <div class="message-body text-center">
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.total')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($purchase->invoice->total); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.discount')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($purchase->invoice->discount_value); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.subtotal')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" readonly="" value="<?php echo e($purchase->invoice->subtotal); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.tax')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($purchase->invoice->tax); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.net')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($purchase->invoice->net); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>


                                
                                
                                
                                
                                
                                
                                
                                
                                
                                

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- end box -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/learning/Desktop/code/zilrsoft/resources/views/purchases/show.blade.php ENDPATH**/ ?>