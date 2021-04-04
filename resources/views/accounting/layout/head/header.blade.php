@php

    //use App\Models\Invoice;
    //use App\Models\ResellerClosingAccount;

       $pending_transactions = [];
   //ManagerPrivateTransactions::
       $pending_purchases = 0;//Invoice::where('invoice_type','pending_purchase')->count()

@endphp

<accounting-header-layout-component
        :manager='@json($loggedManager)'
        :disable-create='@json(auth()->user()->id === 19)'
        :can-manage-managers="{{ auth()->user()->canDo('manage managers')}}"
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
        :can-view-accounting="{{ auth()->user()->canDo('view accounting')}}"
        :csrf='@json(csrf_token())'
        :pending-transactions='@json($headerResellerData)'
        :pending-purchases='@json($pending_purchases)'
        :can-confirm-pending-purchases='{{auth()->user()->canDo('confirm purchase')}}'
        :username='@json(auth()->user()->locale_name)'
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
>
</accounting-header-layout-component>