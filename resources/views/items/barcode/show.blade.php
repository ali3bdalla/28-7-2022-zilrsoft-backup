@extends('layouts.master2')



@section('title',$item->ar_name)
@section('desctipion',$item->barcode)
@section('route',route('management.items.barcode.index'))


@section('content')
    <div class="box">
        <barcode-printer-component :item='@json($item)'></barcode-printer-component>
    </div>

@stop
