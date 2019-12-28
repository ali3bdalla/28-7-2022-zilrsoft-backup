<?php $__env->startSection('title',__('pages/categories.create')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <div class="panel panel-custom-form">
        <form method="post" action="<?php echo e(route('accounting.categories.store')); ?>" class="panel-body">
            <?php echo csrf_field(); ?>
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
                               placeholder="<?php echo e(trans('pages/categories.ar_name')); ?>" value="<?php echo e(old('ar_name') != ""
                               ? old('ar_name') : $category->ar_name); ?>">
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
                               placeholder="<?php echo e(trans('pages/categories.name')); ?>" value="<?php echo e(old('name') != "" ?
                               old('name') : $category->name); ?>">
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
                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['ar_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <textarea class="form-control" name="ar_description"
                                  placeholder="<?php echo e(trans('pages/categories.ar_description')); ?>"><?php echo e(old('ar_description') !=
                                   "" ? old('ar_description') : $category->ar_description); ?></textarea>
                        <?php $__errorArgs = ['ar_description'];
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
                    <div class="form-group <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <textarea class="form-control" name="description" placeholder="<?php echo e(trans('pages/categories.description')); ?>"><?php echo e(old('description') != "" ? old('description') : $category->description); ?></textarea>
                        <?php $__errorArgs = ['description'];
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
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tempCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                        <?php if(!empty(old('parent_id')) && old('parent_id')==$tempCategory['id'] && old
                                        ('parent_id')!=0): ?>
                                        selected
                                        <?php elseif($category->parent_id==$tempCategory['id'] && empty(old('parent_id'))): ?>
                                        selected
                                        <?php endif; ?>
                                        class="form-control" value="<?php echo e($tempCategory['id']); ?>"><?php echo e($tempCategory['locale_name']); ?></option>
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
                <input type="hidden" name="isCloned" value="true"/>
                <input type="hidden" name="cloned_category" value="<?php echo e($category->id); ?>"/>
                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <button type="submit" class="btn btn-custom-primary"><?php echo e(trans('pages/categories.create')); ?></button>
                    </div>
                    <div class="col-md-2 col-md-offset-3">

                        <a href="<?php echo e(route('accounting.categories.index')); ?>" class="btn btn-custom-default"><?php echo e(trans
                        ('reusable.cancel')); ?></a>

                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/categories/clone.blade.php ENDPATH**/ ?>