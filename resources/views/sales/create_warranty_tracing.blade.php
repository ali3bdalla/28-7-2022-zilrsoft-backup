@extends('accounting.layout.master')

@section('title',"تتبع الضمان")


@section('page_css')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop



@section('content')

    <accounting-warranty-tracing-component
            :gateways='@json($gateways)'
            :creator='@json($invoice->creator)'
            :items='@json($items)'
            :invoice='@json($invoice)'>

    </accounting-warranty-tracing-component>

@stop
