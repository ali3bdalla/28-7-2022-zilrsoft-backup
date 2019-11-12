@extends('layouts.master2')
@section('title')

    {{$item->locale_name}} <small>{{$item->barcode}}</small>

    @stop
@section('desctipion',$item->barcode)
@section('route',route('management.items.index'))



@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/items'))`
    </script>
@stop


{{--@section("title")--}}
{{--    {{ $item->locale_name }}--}}
{{--@endsection--}}



@section("page_js")
@endsection








@section("content")
    <div class="">
        <item-movement-history-component :item='@json($item)'></item-movement-history-component>
    </div>

@endsection



