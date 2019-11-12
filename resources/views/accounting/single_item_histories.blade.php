@extends('layouts.master2')


@section('title',$item->name)


@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/items'))`
    </script>
@stop

{{--@section('translator')--}}
{{--    <script defer>--}}
{{--        window.translator = '@json(trans('pages/invoice'))'--}}
{{--    </script>--}}
{{--@stop--}}




@section('content')


    <div class="">
        <item-accounting-cost-history-component :item='@json($item)' :activities='@json($activities)
                '></item-accounting-cost-history-component>
    </div>

@stop



