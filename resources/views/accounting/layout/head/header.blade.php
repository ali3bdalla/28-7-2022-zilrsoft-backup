<?php use App\Invoice;
	use App\ManagerPrivateTransactions;

	$pending_transactions = ManagerPrivateTransactions::where([['is_pending',true],['transaction_type',
	'transfer'],
	['receiver_id',auth()->user()->id]])->with('creator',
	'receiver')->get();
	$pending_purchases = Invoice::where('invoice_type','pending_purchase')->count();
?>

<accounting-header-layout-component
        :can-manage-managers="{{ auth()->user()->canDo('manage managers')}}"
        :can-view-accounting="{{ auth()->user()->canDo('view accounting')}}"
        :csrf='@json(csrf_token())'
        :pending-transactions='@json($pending_transactions)'
        :pending-purchases='@json($pending_purchases)'
        :can-confirm-pending-purchases='{{auth()->user()->canDo('confirm purchase')}}'
        :username='@json(auth()->user()->locale_name)'
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
>
</accounting-header-layout-component>