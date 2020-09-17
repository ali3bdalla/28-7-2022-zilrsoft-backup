<form action="<?php echo e(route('authentication.logout')); ?>" method="post">
    <?php echo csrf_field(); ?>
    <button class="btn btn-default btn-flat" type="submit">
        <?php echo app('translator')->get('layouts.logout'); ?>
    </button>
</form><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/layouts/buttons/logout_button.blade.php ENDPATH**/ ?>