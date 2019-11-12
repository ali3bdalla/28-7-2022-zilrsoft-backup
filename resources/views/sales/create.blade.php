@extends('layouts.master2')



@section('title',__('sidebar.sales'))
@section('desctipion',__('pages/invoice.create'))
@section('route',route('management.sales.index'))

@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop


@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <create-sale-form-component :salesmen='@json($salesmen)' :gateways='@json($gateways)'
                                            :expenses='@json($expenses)'
                                            :creator='@json(auth()->user()->with
                ('department','branch')->first())' :clients='@json($clients)'></create-sale-form-component>
            </div>
        </div>
    </div>

@stop
