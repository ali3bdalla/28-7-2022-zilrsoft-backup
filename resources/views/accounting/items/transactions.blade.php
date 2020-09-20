@extends('accounting.layout.master')



@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/items'))`
    </script>
@stop





@section("content")
        <accounting-item-transactions-component :item='@json($item)'></accounting-item-transactions-component>
@endsection



