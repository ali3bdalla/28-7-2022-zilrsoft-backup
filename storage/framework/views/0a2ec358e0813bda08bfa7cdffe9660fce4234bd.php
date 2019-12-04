<?php $__env->startSection('title',__('sidebar.users')); ?>
<?php $__env->startSection('desctipion','users list'); ?>
<?php $__env->startSection('route',route('management.users.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="box">

        <div class="panel-heading">
            <a href="<?php echo e(route('management.users.create')); ?>"
               class="button is-primary btn-outline-primary"><i
                        class="fa fa-plus-circle"></i> <?php echo e(trans('pages/users.create')); ?></a>
        </div>
        <div class="">
            <?php if(empty(!$users)): ?>
                <div class="table-responsive text-center">
                    <table class="table table-bordered text-center table-hover table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>الصلاحيات</th>
                            <th>التاريخ</th>
                            <th>المستخدم</th>
                            <th>خيارات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($user->name); ?></td>

                                <td><?php if(!$user->is_system_user): ?><?php echo e($user->phone_number); ?><?php endif; ?></td>
                                <td>
                                    <?php $__currentLoopData = $user->membership_type(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $membership; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td><?php echo e($user->created_date); ?></td>
                                <td><?php echo e($user->creator_user()); ?></td>

                                <td>
                                    <a href="<?php echo e(route('management.users.edit',$user->id)); ?>" class="button is-primary
                                    btn-sm"><i
                                                class="fa fa-edit"></i>
                                        &nbsp;
                                        &nbsp;
                                        تعديل
                                    </a>

                                    <a href="<?php echo e(route('management.users.show',$user->id)); ?>" class="button is-info
                                    btn-sm"><i class="fa fa-eye"></i> &nbsp; &nbsp; عرض
                                    </a>

                                    <?php if(!$user->is_system_user): ?>
                                        <form class="" action="<?php echo e(route('management.users.destroy',
                                        $user->id)); ?>" method="post">
                                            <?php echo method_field("DELETE"); ?>
                                            <?php echo csrf_field(); ?>

                                            <div class="form-inline" style="margin-top: 5px">
                                                <button class="button is-danger btn-sm"><i class="fa fa-trash"></i>
                                                    &nbsp; &nbsp; حذف
                                                </button>
                                            </div>
                                        </form>

                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>


                    </table>
                    <?php echo e($users->onEachSide(1)->appends(Request::except('page'))->links()); ?>

                </div>
            <?php else: ?>


            <?php endif; ?>


        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/learning/Desktop/code/zilrsoft/resources/views/users/index.blade.php ENDPATH**/ ?>