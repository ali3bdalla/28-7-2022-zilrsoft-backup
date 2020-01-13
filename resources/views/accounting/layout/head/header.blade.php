<?php $pending_transactions =  \App\ManagerPrivateTransactions::where([['is_pending',true],['transaction_type',
     'transfer'],
     ['receiver_id',auth()->user()->id]])->with('creator',
     'receiver')->get(); ?>

<accounting-header-layout-component
        :can-manage-managers="{{ auth()->user()->canDo('manage managers')}}"
        :can-view-accounting="{{ auth()->user()->canDo('view accounting')}}"
        :csrf='@json(csrf_token())'
        :pending-transactions='@json($pending_transactions)'
        :username='@json(auth()->user()->locale_name)'
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
>
</accounting-header-layout-component>