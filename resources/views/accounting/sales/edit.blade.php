@extends('accounting.layout.master')

@section('title',__('pages/invoice.return'))


@section('page_css')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop



@section('content')

    <accounting-sales-return-component
            :user='@json($sale->client)'
            :gateways='@json($gateways)'
            :expenses='@json($expenses)'
            :creator='@json($invoice->creator)'
            :pitems='@json($items)'
            :invoice='@json($invoice)'
            :department='@json($invoice->department)'
            :sale='@json($sale)'>

    </accounting-sales-return-component>

@stop
