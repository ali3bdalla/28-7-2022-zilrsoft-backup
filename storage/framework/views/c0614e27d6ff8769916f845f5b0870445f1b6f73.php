<?php $__env->startSection('title',__('sidebar.users')); ?>
<?php $__env->startSection('desctipion','users list'); ?>
<?php $__env->startSection('route',route('management.users.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="box">

        <div class="ibox-head">
            <div class="ibox-title"><?php echo e(trans('users.table_list',['users'=>'All Users'])); ?></div>
            <a href="<?php echo e(route('management.users.create')); ?>"
               class="float-right button is-primary btn-outline-primary  create-new-button"><i
                        class="fa fa-plus-circle"></i> <?php echo e(trans('users.create_new_user')); ?></a>
        </div>
        <div class="ibox-body">
            <?php if(empty(!$users)): ?>
                <div class="table-responsive text-center">
                    <table class="table table-bordered text-center table-hover table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>username</th>
                            <th>Phone Number</th>
                            <th>membership type</th>
                            <th>Created At</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($user->id); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->phone_number); ?></td>
                                <td>
                                    <?php $__currentLoopData = $user->membership_type(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $membership; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td><?php echo e($user->created_date); ?></td>
                                <td><?php echo e($user->creator_user()); ?></td>

                                <td>
                                    <?php if(!$user->is_system_user): ?>

                                        


                                        <a href='<?php echo e(route('management.users.update_payments_accounts',$user->id)); ?>'
                                           class="btn
                                    btn-primary btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i
                                                    class="fa fa-info-circle font-14"></i></a>


                                    <!-- <a href='<?php echo e(route('management.users.show',$user->id)); ?>' class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip" data-original-title="view"><i class="fa fa-credit-card font-14"></i></a> -->



                                    <!-- <?php if($user->is_manager && !$user->is_supervisor ): ?>
                                        <a class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></a>
                                    <?php endif; ?> -->

                                    <!-- <form class='form-inline delete-table-form' method="post" action="<?php echo e(route('management.users.destroy',$user->id)); ?>">
                                    <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                            <button class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash font-14"></i></button>
                                            </form> -->
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

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/users/index.blade.php ENDPATH**/ ?>