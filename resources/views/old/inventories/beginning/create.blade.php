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
                <create-beginning-inventory-form-component
                        :user='@json($user)'
                        :creator='@json($creator)'
                >
                </create-beginning-inventory-form-component>

            </div>
        </div>
    </div>

@stop
