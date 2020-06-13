<?php use App\Invoice;
	use App\ManagerPrivateTransactions;

	$pending_transactions = ManagerPrivateTransactions::where([['is_pending',true],['transaction_type',
	'transfer'],
	['receiver_id',auth()->user()->id]])->with('creator',
	'receiver')->get();
	$pending_purchases = Invoice::where('invoice_type','pending_purchase')->count();
?>

<accounting-header-layout-component
        :can-manage-managers="<?php echo e(auth()->user()->canDo('manage managers')); ?>"
        :can-view-accounting="<?php echo e(auth()->user()->canDo('view accounting')); ?>"
        :csrf='<?php echo json_encode(csrf_token(), 15, 512) ?>'
        :pending-transactions='<?php echo json_encode($pending_transactions, 15, 512) ?>'
        :pending-purchases='<?php echo json_encode($pending_purchases, 15, 512) ?>'
        :can-confirm-pending-purchases='<?php echo e(auth()->user()->canDo('confirm purchase')); ?>'
        :username='<?php echo json_encode(auth()->user()->locale_name, 15, 512) ?>'
        :can-view-system-events="<?php echo e(auth()->user()->canDo('view system events')); ?>"
>
</accounting-header-layout-component><?php /**PATH /usr/local/var/www/vhosts/zilrsoft_v2/resources/views/accounting/layout/head/header.blade.php ENDPATH**/ ?>