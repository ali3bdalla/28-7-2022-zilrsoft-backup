<?php $__env->startSection('content'); ?>
<div class="row  justify-content-center">
    <div class='col-md-8'>
    	 <div class="card">
    	 	<div class='card-header'>
    	 		<h3><?php echo e($user->name); ?></h3>
    	 		<p><?php echo e($user->phone_number); ?></p>
    	 	</div>


    	 	<div class='card-body'>

    	 	</div>



    	 	<div class='card-footer'>

    	 	</div>


    	 </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/users/show.blade.php ENDPATH**/ ?>