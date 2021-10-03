<accounting-header-layout-component
        :manager='@json($loggedManager)'
        :can-manage-managers="{{ auth()->user()->canDo('manage managers')}}"
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
        :can-view-accounting="{{ auth()->user()->canDo('view accounting')}}"
        :csrf='@json(csrf_token())'
        :pending-transactions='@json([])'
        :pending-purchases='@json(0)'
        :can-confirm-pending-purchases='{{auth()->user()->canDo('confirm purchase')}}'
        :username='@json(auth()->user()->locale_name)'
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
>
</accounting-header-layout-component>
