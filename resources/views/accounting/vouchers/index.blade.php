@extends('accounting.layout.master')


@section('title',__('sidebar.vouchers'))



@section('buttons')
    @can('create voucher')
        <a href="{{ route("accounting.vouchers.create") }}?voucher_type=payment" class="btn btn-custom-primary"><i
                    class='fa
                    fa-plus-circle'></i>&nbsp; {{ __('pages/vouchers.create_payment') }}</a>
        <a href="{{ route("accounting.vouchers.create") }}?voucher_type=receipt" class="btn  btn-custom-default"><i
                    class='fa
                    fa-plus-circle'></i>&nbsp; {{ __('pages/vouchers.create_receipt') }}</a>
    @endcan
@endsection
@section('content')

    <accounting-vouchers-datatable-component
            :identities='@json($identities)'
            :creators='@json($creators)'
    >

    </accounting-vouchers-datatable-component>

@stop


