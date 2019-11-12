@extends('layouts.master2')



@section('title',__('sidebar.sales'))
@section('desctipion',__('sidebar.sales'))
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
                <edit-sale-form-component
                    :user='@json($sale->client)'
                    :creator='@json($invoice->creator)'
                    :pitems='@json($items)'
                    :invoice='@json($invoice)'
                    :department='@json($invoice->department)'
                    :sale='@json($sale)'>

                </edit-sale-form-component>
            </div>
        </div>
    </div>

@stop
