<?php $__env->startSection('title',__('sidebar.kits')); ?>
<?php $__env->startSection('desctipion','items'); ?>
<?php $__env->startSection('route',route('management.items.index')); ?>

<?php $__env->startSection('content'); ?>
    <div class="box">
        <div class="card-body">
            <a href="<?php echo e(route('management.kits.create')); ?>" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/items.create_kit')); ?></a>
            <span class="subtitle"></span>

            <br>
        </div>
    </div>

    <div class="card">
        <div class="">


            <?php if(isset($items)): ?>
                <table class="table is-borderd table-bordered text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th><?php echo e(__('pages/items.name')); ?></th>
                        <th><?php echo e(__('pages/items.barcode')); ?></th>
                        <th><?php echo e(__('pages/items.items_count')); ?></th>
                        <th><?php echo e(__('reusable.date')); ?></th>
                        <th><?php echo e(__('reusable.creator')); ?></th>
                        <th><?php echo e(__('reusable.manage')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->id); ?></td>
                            <td>
                                <p><?php echo e($item->name); ?></p>

                            </td>
                            <td><?php echo e($item->barcode); ?></td>
                            <td><?php echo e($item->items()->count()); ?></td>
                            <td><?php echo e($item->created_at); ?></td>
                            <td><?php echo e($item->creator->name); ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="button is-primary" type="button" id="optionsMenu<?php echo e($item->id); ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; <?php echo e(__('reusable.manage')); ?>

                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu<?php echo e($item->id); ?>">
                                        <a class="dropdown-item" href="<?php echo e(route("management.kits.edit",$item->id)); ?>"><i class="fa fa-eye"></i> &nbsp;<?php echo e(__('reusable.view')); ?></a>

                                        <form method="post" action="<?php echo e(route
                                        ('management.kits.destroy',$item->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="dropdown-item is-danger" type="submit"><i
                                                        class="fa fa-trash"></i> &nbsp; <?php echo e(__('reusable.delete')); ?>

                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($items->links()); ?>

            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/kits/show.blade.php ENDPATH**/ ?>