@extends('layouts.master2')


@section('title',__('pages/payments.create_receipt'))
@section('desctipion',__('pages/payments.create'))
@section('route',route('management.payments.index'))


@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/payments'))`
    </script>
@stop

@section('content')


    {{--    {{ $clients }}--}}

{{--    <div class="box">--}}
{{--        <div class="card-body">--}}
            <create-receipt-form-component1
                    :organization_gateways='@json($organization_gateways)'
                    :organization-banks='@json($organization_accounts)'
                    :country-banks='@json($all_banks)'
                    :clients='@json($clients)'
                    :vendors='@json($vendors)
                '></create-receipt-form-component1>
{{--        </div>--}}
{{--    </div>--}}


@stop
