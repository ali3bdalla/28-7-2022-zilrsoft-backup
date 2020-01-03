@extends('accounting.layout.master')


@section('title','طباعة الباركود')


@section('content')
    <div class="panel">
        @foreach($items as $item)

            <div class="panel-body">
                <accounting-print-single-barcode-layout-component
                        :item='@json($item)'
                >
                </accounting-print-single-barcode-layout-component>
            </div>
        @endforeach
    </div>
@stop
