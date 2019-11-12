@extends('layouts.master2')



@section('title',__("sidebar.purchases"))
@section('route',route('management.purchases.index'))


@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop



@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
            <edit-purchase-form-component
                :user='@json($purchase->vendor)'
                :creator='@json($invoice->creator)'
                :pitems='@json($items)'
                :invoice='@json($invoice)'
                :department='@json($invoice->department)'
                :purchase='@json($purchase)'>

            </edit-purchase-form-component>
            </div>
        </div>
    </div>

@stop
