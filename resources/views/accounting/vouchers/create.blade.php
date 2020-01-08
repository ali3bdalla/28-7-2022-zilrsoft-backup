@extends('accounting.layout.master')

@section('title',__('pages/vouchers.create_' . $voucher_type))



@section('page_css')
    <script defer>
        window.translator = `@json(trans('pages/vouchers'))`
    </script>
@stop



@section('content')


    <accounting-vouchers-create-component
            :payment_type='@json($voucher_type)'
            :accounts='@json($accounts)'
            :voucher_types='@json($voucher_types)'
            :users='@json($users)'
    >
    </accounting-vouchers-create-component>


@stop
