@extends('old.layouts.master2')


@section('title',__('pages/items.create_kit'))
@section('desctipion','here you can create new items kit package')
@section('route',route('management.kits.index'))


@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/items'))'
    </script>
@stop


@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <create-kit-component
                        :creator='@json(auth()->user()->with('department','branch')->first())'></create-kit-component>
            </div>
        </div>
    </div>

@stop
