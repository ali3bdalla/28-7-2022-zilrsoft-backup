@extends('layouts.master2')



@section('title',__('sidebar.beginning_inventory'))
@section('desctipion',__('pages/invoice.create'))
@section('route',route('management.inventories.beginning.index'))


@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop



@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <update-beginning-inventory-form-component
                        :inventory='@json($inventory)'
                        :creator='@json($inventory->invoice->creator()->with('department')->first())'
                        :user='@json($inventory->receiver)'
                        :init_items='@json($items)'
                >
                </update-beginning-inventory-form-component>

            </div>
        </div>
    </div>

@stop
