<accounting-header-layout-component
        :can-manage-managers="<?php echo e(auth()->user()->canDo('manage managers')); ?>"
        :can-view-accounting="<?php echo e(auth()->user()->canDo('view accounting')); ?>"
        :csrf='<?php echo json_encode(csrf_token(), 15, 512) ?>'
        :username='<?php echo json_encode(auth()->user()->name, 15, 512) ?>'
        :can-view-system-events="<?php echo e(auth()->user()->canDo('view system events')); ?>"
>
</accounting-header-layout-component><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/layout/head/header.blade.php ENDPATH**/ ?>