<?php $__env->startSection('title',__('sidebar.expenses')); ?>
<?php $__env->startSection('buttons'); ?>
    <a href="<?php echo e(route("accounting.expenses.create")); ?>" class="btn btn-custom-primary"><i class='fa
                fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/expenses.create')); ?></a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection("content"); ?>

    <div class="panel">


        <?php if(isset($expenses) and !empty($expenses)): ?>

            <table class="table table-dark table-striped table-bordered text-center table-hover table-" width='100%'>
                <thead class="">
                <tr>
                    <td><?php echo e(__('pages/expenses.id')); ?></td>
                    <td><?php echo e(__('pages/expenses.name')); ?></td>
                    <td><?php echo e(__('pages/expenses.ar_name')); ?></td>
                    <td><?php echo e(__('reusable.date')); ?></td>
                    <td><?php echo e(__('reusable.creator')); ?></td>
                    <td><?php echo e(__('reusable.manage')); ?></td>
                </tr>
                </thead>

                <tbody>
                <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($expense['id']); ?></td>
                        <td><?php echo e($expense['name']); ?></td>
                        <td><?php echo e($expense['ar_name']); ?></td>
                        <td class="data_field_center"><?php echo e($expense['created_at']); ?></td>
                        <td>
                            <a href="<?php echo e(route("accounting.managers.show",$expense->creator_id)); ?>"><?php echo e($expense->creator->name); ?></a>
                        </td>
                        <td>

                            <form method="post" style="display: inline;" class=""
                                  action="<?php echo e(route('accounting.expenses.destroy',$expense->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('delete'); ?>
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i> &nbsp;
                                    <?php echo e(__('reusable.delete')); ?></button>
                            </form>


                        </td>
                    </tr>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
            </table>
    </div>
    <?php endif; ?>


<?php $__env->stopSection(); ?>


<?php echo $__env->make("accounting.layout.master", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/expenses/index.blade.php ENDPATH**/ ?>