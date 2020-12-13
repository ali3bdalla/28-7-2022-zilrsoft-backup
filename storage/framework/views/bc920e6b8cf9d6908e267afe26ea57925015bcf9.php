<?php

    //use App\Models\Invoice;
    //use App\Models\ResellerClosingAccount;

       $pending_transactions = [];
   //ManagerPrivateTransactions::
       $pending_purchases = 0;//Invoice::where('invoice_type','pending_purchase')->count()

?>

<accounting-header-layout-component
        :manager='<?php echo json_encode($loggedManager, 15, 512) ?>'
        :disable-create='<?php echo json_encode(auth()->user()->id === 19, 15, 512) ?>'
        :can-manage-managers="<?php echo e(auth()->user()->canDo('manage managers')); ?>"
        :can-view-accounting="<?php echo e(auth()->user()->canDo('view accounting')); ?>"
        :csrf='<?php echo json_encode(csrf_token(), 15, 512) ?>'
        :pending-transactions='<?php echo json_encode($headerResellerData, 15, 512) ?>'
        :pending-purchases='<?php echo json_encode($pending_purchases, 15, 512) ?>'
        :can-confirm-pending-purchases='<?php echo e(auth()->user()->canDo('confirm purchase')); ?>'
        :username='<?php echo json_encode(auth()->user()->locale_name, 15, 512) ?>'
        :can-view-system-events="<?php echo e(auth()->user()->canDo('view system events')); ?>"
>
</accounting-header-layout-component><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/accounting/layout/head/header.blade.php ENDPATH**/ ?>