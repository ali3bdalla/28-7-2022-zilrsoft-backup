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
            <a href="<?php echo e(route("management.sales.quotation_create")); ?>" class="button is-info pull-right"><i class='fa
                    fa-plus-circle'></i>&nbsp; <?php echo e(__('reusable.create_invoice')); ?></a>
            <br>
            <br>

        </div>
    </div>

    <div class="card">


        
        <?php if(!empty($sales)): ?>




            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo e(__('pages/invoice.invoice_number')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.client')); ?></th>
                        <th class="text-center "><?php echo e(__('pages/invoice.date')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.net')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.manage')); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($sale->invoice_id); ?></td>
                            <td><?php echo e($sale->client->name); ?></td>
                            <td class="datedirection"><?php echo e($sale->created_at); ?></td>
                            <td><?php echo e($sale->invoice->net); ?></td>
                            <td width="8%">

                                <a href="<?php echo e(route('management.sales.view_quotation',$sale->id)); ?>" class="button
                                is-primary">
                                    <i class="fa fa-eye"></i> &nbsp; <?php echo e(__('reusable.view')); ?>

                                </a>


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

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/sales/quotations.blade.php ENDPATH**/ ?>