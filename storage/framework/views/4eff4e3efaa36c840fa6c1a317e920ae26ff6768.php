<?php $__env->startSection('title',__('sidebar.filters')); ?>
<?php $__env->startSection('desctipion','our organization filters list'); ?>
<?php $__env->startSection('route',route('management.filters.index')); ?>

<?php $__env->startSection("content"); ?>
    <div class="box">
        <div class="card-body">
            <a href="<?php echo e(route("management.filters.create")); ?>" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/filters.create')); ?></a>
            <span class="subtitle"> </span>

            <br>
        </div>
    </div>

    <div class="card">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


        <?php if(isset($filters) and !empty($filters)): ?>

            <table class="table table-dark table-striped table-bordered text-center table-hover table-" width='100%'>
                <thead class="thead-dark">
                <tr>
                    <td><?php echo e(__('pages/filters.id')); ?></td>
                    <td><?php echo e(__('pages/filters.name')); ?></td>
                    <td><?php echo e(__('pages/filters.ar_name')); ?></td>
                    <td ><?php echo e(__('reusable.date')); ?></td>
                    <td><?php echo e(__('reusable.creator')); ?></td>
                    <td><?php echo e(__('reusable.manage')); ?></td>
                </tr>
                </thead>

                <tbody>
                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($filter['id']); ?></td>
                        <td><?php echo e($filter['name']); ?></td>
                        <td><?php echo e($filter['ar_name']); ?></td>
                        <td class="datedirection"><?php echo e($filter['created_at']); ?></td>
                        <td>
                            <a href="<?php echo e(route("management.users.show",$filter->creator_id)); ?>"><?php echo e($filter->creator->name); ?></a>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="button is-primary" type="button" id="optionsMenu<?php echo e($filter->id); ?>"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> &nbsp; <?php echo e(__('reusable.manage')); ?>

                                </button>
                                <div class="dropdown-menu" aria-labelledby="optionsMenu<?php echo e($filter->id); ?>">
                                    <a class="dropdown-item" href="<?php echo e(route("management.filters.edit",
                                            $filter['id'])); ?>"><i class="fa fa-edit"></i> &nbsp; <?php echo e(__('reusable.update')); ?></a>

                                    <form method="post" style="display: inline;" class="">
                                        <button class="dropdown-item is-danger" type="submit"><i class="fa
                                            fa-trash"></i> &nbsp; <?php echo e(__('reusable.delete')); ?></button>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
            </table>
            <?php echo e($filters->links()); ?>

    </div>
    <?php endif; ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.master2", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/filters/show.blade.php ENDPATH**/ ?>