@extends('accounting.layout.master')

@section('title',__('pages/invoice.return'))


@section('page_css')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop



@section('content')

    <accounting-sales-return-component
            :gateways='@json($gateways)'
            :creator='@json($invoice->creator)'
            :items='@json($items)'
            :invoice='@json($invoice->load('department','branch'))'>

    </accounting-sales-return-component>

@stop
