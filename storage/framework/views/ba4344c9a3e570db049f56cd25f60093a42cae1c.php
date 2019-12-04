<?php $__env->startSection('title','فواتير عرض السعر'); ?>
<?php $__env->startSection('desctipion','sales list'); ?>
<?php $__env->startSection('route',route('management.sales.index')); ?>



<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/invoice'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('page_css'); ?>
    <style>
        th {
            text-align: center !important;
        }

        .dropdown-menu {
            transform: translate3d(54px, 41px, 0px) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="box">
        <div class="card-body">
            <a href="<?php echo e(route("management.sales.create")); ?>" class="button is-info pull-right"><i class='fa
                    fa-plus-circle'></i>&nbsp; <?php echo e(__('reusable.create_invoice')); ?></a>
            <span class="subtitle"> <?php echo e(__('sidebar.sales')); ?></span>

        </div>
    </div>

    <div class="card">



        
        <?php if(!empty($sales)): ?>




            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">#</th>
                        <th class="text-center"><?php echo e(__('pages/invoice.invoice_number')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.client')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.salesman')); ?></th>
                        <th class="text-center "><?php echo e(__('pages/invoice.date')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.net')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.issued_status')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.is_paid')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.invoice_type')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.creator')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.vat')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.manage')); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($sale->id); ?></td>
                            <td><?php echo e($sale->invoice_id); ?></td>
                            <td><?php echo e($sale->client->name); ?></td>
                            <td><?php echo e($sale->salesman->name); ?></td>
                            <td class="datedirection"><?php echo e($sale->created_at); ?></td>
                            <td><?php echo e($sale->invoice->net); ?></td>
                            <td>
                                <?php echo e(__('pages/invoice.' .$sale->invoice->issued_status)); ?>

                            </td>
                            <td><?php echo e($sale->invoice->current_status  == 'paid' ? __('pages/invoice.yes'): __('pages/invoice.no')); ?></td>
                            <td><?php echo e($sale->invoice->description); ?></td>
                            <td><?php echo e($sale->invoice->creator->name); ?></td>
                            <td><?php echo e($sale->invoice->tax); ?></td>
                            <td width="8%">

                                <div class="dropdown">
                                    <button class="button is-primary" type="button" id="optionsMenu<?php echo e($sale->id); ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; <?php echo e(__('reusable.manage')); ?>

                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu<?php echo e($sale->id); ?>">
                                        <a class="dropdown-item" href="<?php echo e(route('management.sales.show',$sale->id)); ?>"><i
                                                    class="fa fa-eye"></i> &nbsp; <?php echo e(__('reusable.view')); ?></a>
                                        <a class="dropdown-item" href="<?php echo e(route('management.sales.edit',$sale->id)); ?>"><i
                                                    class="fa
    							     fa-redo-alt"></i> &nbsp; <?php echo e(__('reusable.return')); ?></a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-trash"></i> &nbsp; <?php echo e(__
                                        ('reusable.delete')); ?></a>
                                        <a class="dropdown-item" href="<?php echo e(route('management.sales.clone',$sale->id)); ?>"><i
                                                    class="fa fa-copy"></i> &nbsp; <?php echo e(__
                                        ('reusable.copy')); ?></a>


                                    </div>
                                </div>


                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($sales->links()); ?>

        <?php endif; ?>

    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/sales/quotations.blade.php ENDPATH**/ ?>