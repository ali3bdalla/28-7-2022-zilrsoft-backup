<?php $__env->startSection('title',__('sidebar.purchases')); ?>
<?php $__env->startSection('desctipion','purchases list'); ?>
<?php $__env->startSection('route',route('management.purchases.index')); ?>

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
            <a href="<?php echo e(route("management.purchases.create")); ?>" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; <?php echo e(__('reusable.create_invoice')); ?></a>


                <br>
        </div>
    </div>

    <div class="card">
        <?php if(!empty($purchases)): ?>
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">#</th>
                        <th class="text-center"><?php echo e(__('pages/invoice.invoice_number')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.vendor')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.receiver')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.date')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.net')); ?></th>
                        <!-- <th class="text-center">Due date</th> -->
                        <th class="text-center"><?php echo e(__('pages/invoice.issued_status')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.is_paid')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.invoice_type')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.creator')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.vat')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.manage')); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($purchase->id); ?></td>
                            <td><?php echo e($purchase->invoice_id); ?></td>
                            <td><?php echo e($purchase->vendor->name); ?></td>
                            <td><?php echo e($purchase->receiver->name); ?></td>
                            <td><?php echo e($purchase->created_at); ?></td>
                            <td><?php echo e($purchase->invoice->net); ?></td>
                            <td>
                                <?php echo e(__('pages/invoice.' .$purchase->invoice->issued_status)); ?>

                            </td>
                            <td><?php echo e($purchase->invoice->current_status  == 'paid' ? __('pages/invoice.yes'): __('pages/invoice.no')); ?></td>
                            <td><?php echo e($purchase->invoice->description); ?></td>


                            <td><?php echo e($purchase->invoice->creator->name); ?></td>
                            <td><?php echo e($purchase->invoice->total_tax); ?></td>

                            <td width="8%">

                                <div class="dropdown">
                                    <button class="button is-primary" type="button" id="optionsMenu<?php echo e($purchase->id); ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; <?php echo e(__('reusable.manage')); ?>

                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu<?php echo e($purchase->id); ?>">
                                        <a class="dropdown-item"
                                           href="<?php echo e(route('management.purchases.show',$purchase->id)); ?>"><i
                                                    class="fa fa-eye"></i> &nbsp; <?php echo e(__('reusable.view')); ?></a>
                                        <a class="dropdown-item" href="<?php echo e(route('management.purchases.edit',$purchase->id)); ?>"><i class="fa fa-redo-alt"></i><?php echo e(__('reusable.return')); ?></a>
                                        <?php if($purchase->invoice->is_updated): ?>
                                            <a class="dropdown-item" href="#"><i class="fa fa-trash"></i> &nbsp; <?php echo e(__('reusable.delete')); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>


                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($purchases->links()); ?>

        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/purchases/index.blade.php ENDPATH**/ ?>