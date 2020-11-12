@extends('accounting.layout.master')

@section('title',__('sidebar.sales'))
@section('buttons')
    @can("create sale")
        <a href="{{route('sales.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.create') }}
        </a>
    @endcan
@stop



@section("content")
    {{ json_encode($loggedManager) }}
    <accounting-sales-datatable-component
            :creators='@json($creators)'
            :vendors='@json($clients)'
            :departments='@json($departments)'
{{--            :creator='@json($loggedManager)'--}}
            :can-view-accounting="{{ auth()->user()->canDo('view item transactions') }}"
            :can-edit="{{ auth()->user()->canDo('edit sale') }}"
    >
    </accounting-sales-datatable-component>
@endsection



@section("after_content")
@endsection