<?php $__env->startSection('title',__('pages/invoice.view_sale')); ?>
<?php $__env->startSection('desctipion','create sale invoice'); ?>
<?php $__env->startSection('route',route('management.sales.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="message">


        <div class="message-header has-background-dark">
            <h4 class="title has-text-white pull-right"><?php echo e($sale->prefix . $sale->invoice_id); ?></h4>

            <div class="columns">
                <div class="column">
                    <a href="<?php echo e(route('management.sales.clone',$sale->id)); ?>" class="button is-primary"> تحويل الى
                        فاتورة</a>
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
                        <?php if(!$item->item->is_expense): ?>
                            <tr <?php if($item->belong_to_kit==true): ?> style="background-color: #48dbfb;color: white !important;" <?php endif; ?>>
                                <th class="has-text-dark">
                                    <?php if($item->belong_to_kit!=true): ?>  <?php echo e($index); ?>

							  <?php $index++;?>
                                    <?php endif; ?>
                                </th>
                                <!-- <th class="has-text-white"></th> -->
                                <th text="item.barcode"
                                    style="text-align: left !important;"><?php echo e($item->item->barcode); ?></th>
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
                                    <input type="text" class="input" placeholder="discount"
                                           value="<?php echo e($item->discount); ?>"
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
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div class="columns">


                    <div data-v-73cd913d="" class="column is-three-quarters">

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

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/sales/quotation_show.blade.php ENDPATH**/ ?>