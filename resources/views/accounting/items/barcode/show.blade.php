@extends('accounting.layout.master')


@section('title',__('sidebar.barcode'))
@section('content')
    <div class="panel">
        @foreach($items as $item)

            <div class="panel-body">
                <accounting-barcode-printer-layout-component
                        :item='@json($item)'
                >
                </accounting-barcode-printer-layout-component>
            </div>
        @endforeach
    </div>
@stop
