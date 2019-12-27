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
                        :creator='@json(auth()->user()->load('department','branch'))
                                '></accounting-kits-create-component>
            </div>
        </div>
    </div>

@stop
