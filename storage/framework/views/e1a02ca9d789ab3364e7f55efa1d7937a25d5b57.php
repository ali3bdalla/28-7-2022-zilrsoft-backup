<?php $__env->startSection('title',__('pages/expenses.create')); ?>
<?php $__env->startSection('desctipion',''); ?>
<?php $__env->startSection('route',route('management.expenses.index')); ?>


<?php $__env->startSection('content'); ?>

    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        <?php echo e(__('pages/expenses.create')); ?>

                    </div>

                    <form method="post" action="<?php echo e(route('management.expenses.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">

                                    <div class="column">
                                        <input
                                                class="input"
                                                value="<?php echo e(old('name')); ?>"

                                                name="name"
                                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                :has-error="true"
                                                :error-message='<?php echo json_encode($message, 15, 512) ?>'
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                placeholder='<?php echo e(__('reusable.name')); ?>'
                                                mode='en'/>

                                    </div>
                                    <div class="column">
                                        <input
                                                class="input"
                                                value="<?php echo e(old('ar_name')); ?>"
                                                <?php $__errorArgs = ['ar_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                :has-error="true"
                                                :error-message='<?php echo json_encode($message, 15, 512) ?>'
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                placeholder='<?php echo e(__('reusable.ar_name')); ?>'
                                                mode='ar'
                                                name='ar_name'/>
                                    </div>
                                </div>


                                <div class="columns">
                                    <div class="column">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" data-toggle="toggle"checked
                                                       name="appear_in_sale">
                                                يظهر في المبيعات
                                            </label>
                                        </div>

                                    </div>
                                    <div class="column">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" data-toggle="toggle"checked
                                                       name="appear_in_purchase">
                                                يظهر في المشتريات
                                            </label>
                                        </div>

                                    </div>
                                </div>


                                <div class="form-group">
                                    <button class="button is-success"><i class="fa fa-check-circle"></i>&nbsp; <?php echo e(__
                                    ('reusable.create')); ?></button>
                                    &nbsp;
                                    <a href="<?php echo e(route('management.expenses.index')); ?>" class="button is-right"><i
                                                class="fa fa-undo-alt"></i>&nbsp; <?php echo e(__('reusable.cancel')); ?></a>
                                </div>
                            </div>
                        </div>

                    </form>
                </article>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/learning/Desktop/code/zilrsoft/resources/views/expenses/create.blade.php ENDPATH**/ ?>