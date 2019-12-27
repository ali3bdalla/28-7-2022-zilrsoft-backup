<accounting-header-layout-component
        :can-manage-managers="{{ auth()->user()->canDo('manage managers')}}"
        :can-view-accounting="{{ auth()->user()->canDo('view accounting')}}"
        :csrf='@json(csrf_token())'
        :username='@json(auth()->user()->name)'
        :can-view-system-events="{{ auth()->user()->canDo('view system events')}}"
>
</accounting-header-layout-component>