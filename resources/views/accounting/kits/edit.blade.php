@extends('accounting.layout.master')


@section('title',__('pages/items.create_kit'))
@section('desctipion','here you can create new items kit package')
@section('route',route('accounting.kits.index'))


@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/items'))'
    </script>
@stop


@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <accounting-kits-create-component
                        :editing-kit="true"
                        :kit='@json($kit)'
                        :init-items='@json($items)'
                        :data='@json($data)'
                        :creator='@json($loggedManager)
                                '></accounting-kits-create-component>
            </div>
        </div>
    </div>

@stop
