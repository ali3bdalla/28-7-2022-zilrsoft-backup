<?php $__env->startSection('title',__('pages/invoice.view_sale')); ?>
<?php $__env->startSection('desctipion','create sale invoice'); ?>
<?php $__env->startSection('route',route('management.sales.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="message">


        <div class="message-header has-background-dark">
            <h4 class="title has-text-white pull-right"><?php echo e($sale->prefix . $sale->invoice_id); ?></h4>

            <div class="columns">
                <div class="column">
                    <?php if(isset($_GET['ask']) && $_GET['ask']=='receipt'): ?>
                        <receipt-printer-component :print="true" :invoice_id='<?php echo json_encode($sale->invoice_id, 15, 512) ?>
                                '></receipt-printer-component>
                    <?php else: ?>
                        <receipt-printer-component :print="true" :invoice_id='<?php echo json_encode($sale->invoice_id, 15, 512) ?>
                                '></receipt-printer-component>
                    <?php endif; ?>
                </div>
                <div class="column">






                        <print-invoice-component :invoice_id="<?php echo e($sale->invoice_id); ?>"
                                                 :print="false"
                                                 :title='<?php echo json_encode( __('pages/invoice.price_invoice'), 15, 512) ?>'></print-invoice-component>



                </div>
                <div class="column">
                    <?php if($sale->invoice_type=='sale'): ?>

                        <a href="<?php echo e(route('management.sales.edit',$sale->id)); ?>" class="button is-warning"><i
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
                                <span class="input-group-addon" id="vendors-list"><?php echo e(__('pages/invoice.client')); ?></span>

                                <input type="text" name="" aria-describedby="time-field" class="form-control"
                                       disabled="" value="<?php echo e($sale->client->name); ?>">

                            </div>


                        </div>
                    </div>

                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field"><?php echo e(__('reusable.date')); ?></span>
                            <input type="text" name="" style="text-align: center !important; direction: ltr
                            !important;" aria-describedby="time-field" class="form-control" disabled=""
                                   value="<?php echo e($sale->created_at); ?>">

                        </div>


                    </div>


                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>client name</label> -->
                            <div class="">
                                <div class="input-group">
                                    <span class="input-group-addon" id="receivers-list"><?php echo e(__('pages/invoice.salesman')); ?></span>
                                    <input type="text" name="" aria-describedby="receivers-list" class="form-control"
                                           disabled="" value="<?php echo e($sale->salesman->name); ?>">

                                </div>

                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department-field"><?php echo e(__('pages/invoice.department')); ?></span>
                                <input type="text" name="" aria-describedby="department-field" readonly
                                       class="form-control" disabled=""
                                       value="<?php echo e($sale->invoice->branch->name); ?>">

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
                        <th class="has-text-white">#</th>
                        <!-- <th class="has-text-white"></th> -->
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


                    <?php $index = 1;?>
                    <?php $__currentLoopData = $sale->invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr <?php if($item->belong_to_kit==true): ?> style="background-color: #48dbfb;color: white !important;" <?php endif; ?>>
                            <th class="has-text-dark">
                                <?php if($item->belong_to_kit!=true): ?>  <?php echo e($index); ?>

                                <?php $index++;?>
                                <?php endif; ?>
                            </th>
                            <!-- <th class="has-text-white"></th> -->
                            <th text="item.barcode" style="text-align: left !important;"><?php echo e($item->item->barcode); ?></th>
                            <th text="item.name"
                                style="text-align: right !important;"><?php echo e($item->item->locale_name); ?></th>
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
                                <input type="text" class="input" placeholder="discount" value="<?php echo e($item->discount); ?>"
                                       disabled="">
                            </th>
                            <th class="has-text-white">
                                <input type="text" class="input" placeholder="subtotal" readonly="" value="<?php echo e($item->subtotal); ?>" disabled="">
                            </th>
                            <th class="has-text-white">
                                <input type="text" class="input" placeholder="vat sale" readonly="" value="<?php echo e($item->item->vts); ?>%" disabled="">
                            </th>
                            <th class="has-text-white">
                                <input type="text" class="input" placeholder="tax" readonly="" value="<?php echo e($item->tax); ?>" disabled="">
                            </th>
                            <th class="has-text-white">
                                <input type="text" class="input" placeholder="net" readonly="" value="<?php echo e($item->net); ?>" disabled="">
                            </th>

                        </tr>
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
                                    <?php if(!empty($sale->invoice->payments)): ?>
                                        <?php $__currentLoopData = $sale->invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-group">

                                                <div class="input-group">
                                                    <span id="1" class="input-group-addon" style="min-width: 250px;
                                                    font-weight: bolder;">
                                                        <?php echo e($payment->payment->gateway->name); ?>

                                                        &nbsp;&nbsp; ( <a target="_blank" href="<?php echo e(route('management.payments.show',
                                                        $payment->payment_id)); ?>">عرض السند</a> )
                                                    </span>
                                                    <input aria-describedby="1" disabled="disabled" type="text"
                                                           class="form-control"value="<?php echo e($payment->amount); ?>" style="font-weight:
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
                                                    <h1 class="title text-center"><?php echo e($sale->invoice->net); ?></h1></div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المدفوع</span>
                                                    <h1 class="title text-center"><?php echo e(money_format("%i",
                                                $sale->invoice->net
                                                                                      - $sale->invoice->remaining)); ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المتبقي / آجل</span>
                                                    <h1 class="title text-center"><input disabled="disabled"
                                                                                         value="<?php echo e($sale->invoice->remaining); ?>"
                                                                                         readonly="readonly" type="text"
                                                                                         class="form-control is-danger has-error onlyhidden"
                                                                                         style="font-size: 32px; height: 36px;">
                                                        <input readonly="readonly" disabled="disabled" type="text"
                                                               class="form-control is-danger has-error onlyhidden"
                                                               style="font-size: 32px; height: 36px; display: none;"></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    
                    <div class="column">
                        <div class="card">
                            
                            
                            
                            <div class="message-body text-center">
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.total')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($sale->invoice->total); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.discount')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($sale->invoice->discount_value); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.subtotal')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" readonly="" value="<?php echo e($sale->invoice->subtotal); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.tax')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($sale->invoice->tax); ?>"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column"><?php echo e(__('pages/invoice.net')); ?></div>
                                        <div class="column">
                                            <input type="text" class="input" value="<?php echo e($sale->invoice->net); ?>"
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

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/sales/show.blade.php ENDPATH**/ ?>