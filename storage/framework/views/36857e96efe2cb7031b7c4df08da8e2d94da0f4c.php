<?php $__env->startSection('title',__('pages/invoice.inventories')); ?>
<?php $__env->startSection('desctipion',__('pages/invoice.inventories')); ?>
<?php $__env->startSection('route',route('management.inventories.beginning.index')); ?>

<?php $__env->startSection('page_css'); ?>
    <style>
        th {
            text-align: center !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="box">
        <div class="card-body">
            <a href="<?php echo e(route('management.inventories.beginning.create')); ?>" class="button is-info pull-right"><i
                        class='fa
                fa-plus-circle'></i>&nbsp; <?php echo e(__('reusable.create_invoice')); ?></a>


            <br>
        </div>
    </div>

    <div class="card">


        <?php if(!empty($inventories)): ?>
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%"><?php echo e(__('pages/invoice.id')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.items_count')); ?></th>
                        <th class="text-center"><?php echo e(__('pages/invoice.total')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.creator')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.date')); ?></th>
                        <th class="text-center"><?php echo e(__('reusable.manage')); ?></th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->index + 1); ?></td>
                            <td><?php echo e($inventory->items->count()); ?></td>
                            <td><?php echo e($inventory->total); ?></td>
                            <td><?php echo e($inventory->creator->name); ?></td>
                            <td class="datedirection"><?php echo e($inventory->created_at); ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="button is-primary" type="button"
                                            id="optionsMenu<?php echo e($inventory->purchase->id); ?>" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; <?php echo e(__('reusable.manage')); ?>

                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu<?php echo e($inventory->purchase); ?>">
                                        <a class="dropdown-item" href="<?php echo e(route('management.purchases.show',
                                    $inventory->purchase->id)); ?>"><i class="fa fa-eye"></i> &nbsp; <?php echo e(__('reusable.view')); ?></a>

                                        <a class="dropdown-item" href="<?php echo e(route('management.inventories.beginning.edit',
                                    $inventory->purchase->id)); ?>"><i class="fa fa-edit"></i> &nbsp; <?php echo e(__('reusable.update')); ?></a>

                                    </div>
                                </div>
                            </td>


                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($inventories->links()); ?>

        <?php endif; ?>

    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/inventories/beginning/index.blade.php ENDPATH**/ ?>