@extends('layouts.master2')



@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/items'))`
    </script>
@stop


@section('title',$kit->name)
@section('desctipion','here you can create new items kit package')
@section('route',route('management.kits.index'))



@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <create-kit-component
                        :kit='@json($kit)'
                        :creator='@json(auth()->user()->with('department','branch')->first())'
                        :is_update_mode="true"
                ></create-kit-component>
            </div>
        </div>
    </div>

@stop
