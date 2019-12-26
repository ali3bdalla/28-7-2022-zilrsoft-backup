@extends('accounting.layout.master')

@section('title',__('sidebar.purchases'))
@section('buttons')
    @can("create purchase")
        <a href="{{route('accounting.purchases.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")

    <accounting-purchases-datatable-component
        :can-edit="{{ auth()->user()->canDo('edit purchase') }}"
    >


    </accounting-purchases-datatable-component>
@endsection





@section("after_content")
@endsection