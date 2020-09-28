<?php $__env->startSection('title',__('pages/categories.edit')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <div class="panel panel-custom-form">
        <form method="post" action="<?php echo e(route('accounting.accounts.update',$account->id)); ?>" class="panel-body">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['ar_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> ">
                        <input class="form-control arabic-input" name="ar_name"
                               value="<?php echo e(empty(old('ar_name')) ?  $account->ar_name :old('ar_name')); ?>"
                               placeholder="<?php echo e(trans('pages/categories.ar_name')); ?>">
                        <?php $__errorArgs = ['ar_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <input class="form-control arabic-input" name="name"
                               value="<?php echo e(empty(old('name')) ?  $account->name :old('name')); ?>"
                               placeholder="<?php echo e(trans('pages/categories.name')); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <select class="form-control" name="parent_id" placeholder="<?php echo e(trans('pages/categories.parent_id')); ?>">
                            <option class="form-control" value="0"><?php echo e(trans('pages/categories.main_category')); ?></option>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                        <?php if(!empty(old('parent_id')) && old('parent_id')==$cat['id']): ?>
                                        selected
                                        <?php elseif($account->parent_id==$cat['id'] && empty(old('parent_id'))): ?>
                                        selected
                                        <?php endif; ?>
                                        class="form-control" value="<?php echo e($cat['id']); ?>"><?php echo e($cat['locale_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['account_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <select class="form-control" name="account_type"
                                placeholder="<?php echo e(trans('pages/accounts.account_type')); ?>">
                            <option
                                    <?php if($account->type=='debit'): ?>
                                    selected

                                    <?php endif; ?>
                                    class="form-control" value="debit">مدين
                            </option>

                            <option
                                    <?php if($account->type=='credit'): ?>
                                    selected

                                    <?php endif; ?>
                                    class="form-control" value="credit">دائن
                            </option>

                        </select>
                        <?php $__errorArgs = ['account_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>


                
                <div class="col-md-6">
                    <select class="form-control" name="sorting_number"
                            placeholder="<?php echo e(trans('pages/accounts.sorting')); ?>">
                        <option value="0"><?php echo e(trans('pages/accounts.sorting')); ?></option>
                        <?php $increment = 1; ?>
                        <?php for($i=$account->parent->children()->count();$i>=1;$i--): ?>

                            <option value="<?php echo e($i); ?>" <?php if($account->sorting_number==$i): ?> selected <?php endif; ?>><?php echo e($increment); ?></option>
		                    <?php $increment++; ?>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <toggle-button
                            :value="<?php echo json_encode($account->is_gateway, 15, 512) ?>"
                            name="is_gateway"
                            :async="true"
                            :font-size="19" :height='30' :labels="{checked: 'خزينة', unchecked: 'حساب عادي '}"
                            :width='140'
                    ></toggle-button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary"><?php echo e(trans('buttons.update')); ?></button>
                </div>
                <div class="col-md-2 col-md-offset-3">
                    <a href="<?php echo e(route('accounting.accounts.index')); ?>" class="btn btn-custom-default"><?php echo e(trans
                        ('buttons.back')); ?></a>

                </div>
            </div>
    </div>
    </form>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/charts/edit.blade.php ENDPATH**/ ?>