<?php $__env->startSection('title',__('pages/categories.create')); ?>
<?php $__env->startSection('desctipion',''); ?>
<?php $__env->startSection('route',route('management.categories.index')); ?>


<?php $__env->startSection('content'); ?>
	<div class="center-page">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<article class="message  is-success">
				<div class="message-header">
					<?php echo e(__('pages/categories.category_details')); ?>

				</div>

				<form method="post" action="<?php echo e(route('management.categories.store')); ?>">
					<?php echo csrf_field(); ?>
					<div class="message-body">
						<div class="form-group">
							<div class="columns">
								<?php if($isClone): ?>
									<input type="hidden" name='cloned_category' value="<?php echo e($category->id); ?>">
								<?php endif; ?>
								<div class="column">
									<input-text-component name='name'
									<?php if(empty(old('name')) && $isClone): ?>
										 value="<?php echo e($category->name); ?>"

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
									 placeholder='<?php echo e(__('pages/categories.name')); ?>' mode='en'></input-text-component>
									
								</div>
								<div class="column">
									<input-text-component 
									<?php if(empty(old('ar_name')) && $isClone): ?>
										 value="<?php echo e($category->ar_name); ?>"

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
										
									<input-textarea-component
									<?php if(empty(old('description')) && $isClone): ?>
										 value="<?php echo e($category->description); ?>"

									<?php else: ?>
										value="<?php echo e(old('description')); ?>"
									<?php endif; ?>



									<?php $__errorArgs = ['description'];
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
									  name='description' placeholder='<?php echo e(__('pages/categories.description')); ?>'
									mode='en'></input-textarea-component>
									
								</div>
								<div class="column">
									<input-textarea-component
									<?php if(empty(old('ar_description')) && $isClone): ?>
										 value="<?php echo e($category->ar_description); ?>"

									<?php else: ?>
										value="<?php echo e(old('ar_description')); ?>"
									<?php endif; ?>
									<?php $__errorArgs = ['ar_description'];
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
									  placeholder='<?php echo e(__('pages/categories.ar_description')); ?>' mode='ar'
									name='ar_description'></input-textarea-component>
								</div>
							</div>

							<div class="columns">
								<div class="column">
									<select-list-component
									:items='<?php echo json_encode($categories, 15, 512) ?>'
									capture-text='locale_name'
									
									<?php if(empty(old("parent_id")) && !empty($category)): ?> 
										<?php if($isClone): ?>
											selected="<?php echo e($category->parent_id); ?>"
										<?php else: ?>
											selected="<?php echo e($category->id); ?>"
										<?php endif; ?>
										
									<?php else: ?> 
										selected=value="<?php echo e(old('parent_id')); ?>"
									<?php endif; ?>
									
									:has-default="true"
									default-capture="<?php echo e(__('pages/categories.main_category')); ?>"
									default-value='0'

									<?php $__errorArgs = ['parent_id'];
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
									  name='parent_id'></select-list-component>	
								</div>
							</div>



							<div class="form-group">
								<button class="button is-success"><i class="fa fa-check-circle"></i>&nbsp; <?php echo e(__
								('reusable.create')); ?></button>
								&nbsp;
								<input type="hidden" name="isClone" value="<?php echo e($isClone); ?>"/>
								<a href="<?php echo e(route('management.categories.index')); ?>" class="button is-right"><i
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

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/categories/create.blade.php ENDPATH**/ ?>