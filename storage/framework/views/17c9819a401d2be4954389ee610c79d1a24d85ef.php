<?php $__env->startSection('title',__('pages/accounts.creation')); ?>
<?php $__env->startSection('route',route('management.accounts.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        <?php echo e(__('pages/accounts.creation')); ?>

                    </div>

                    <form method="post" action="<?php echo e(route('management.accounts.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">
                                    <?php if($isClone): ?>
                                        <input type="hidden" name='cloned_account' value="<?php echo e($account->id); ?>">
                                    <?php endif; ?>
                                    <div class="column">
                                        <input-text-component name='name'
                                                              <?php if(empty(old('name')) && $isClone): ?>
                                                              value="<?php echo e($account->name); ?>"

                                                              <?php else: ?>
                                                              value="<?php echo e(old('name')); ?>"
                                                              <?php endif; ?>

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
                                                              placeholder='<?php echo e(__('pages/categories.name')); ?>'
                                                              mode='en'></input-text-component>

                                    </div>
                                    <div class="column">
                                        <input-text-component
                                                <?php if(empty(old('ar_name')) && $isClone): ?>
                                                value="<?php echo e($account->ar_name); ?>"
                                                <?php else: ?>
                                                value="<?php echo e(old('ar_name')); ?>"
                                                <?php endif; ?>
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
                                                placeholder='<?php echo e(__('pages/categories.ar_name')); ?>' mode='ar'
                                                name='ar_name'></input-text-component>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">

                                        <div class="select is-fullwidth">
                                            <select name="parent_id">
                                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($account['id']); ?>"
                                                    <?php if(\Illuminate\Support\Facades\Request::input("parent_id")
                                                    ==$account['id']): ?>
                                                        selected
                                                        <?php endif; ?>
                                                    ><?php echo e($account['ar_name']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            هل هذه بوابة دفع ؟
                                        </div>
                                        <div class="col-md-3">
                                            <toggle-button name="is_gateway" font-size="19" height='30' width='70'
                                                           :labels="{checked: 'نعم', unchecked: 'لا'}"

                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="button is-success"><i
                                                class="fa fa-check-circle"></i>&nbsp; <?php echo e(__('reusable.create')); ?>

                                    </button>
                                    &nbsp;
                                    <input type="hidden" name="isClone" value="<?php echo e($isClone); ?>"/>
                                    <a href="<?php echo e(route('management.accounts.index')); ?>"
                                       class="button is-right"><i
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

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/accounts/create.blade.php ENDPATH**/ ?>