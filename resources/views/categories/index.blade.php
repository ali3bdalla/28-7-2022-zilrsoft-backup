@extends('layouts.master2')




@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/categories'))'
    </script>
@stop


@section('title', __('sidebar.categories'))
@section('desctipion',__('sidebar.categories'))
@section('route',route('management.categories.index'))


@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{route('management.categories.create')}}" class="button is-primary  pull-right"><i
                        class='fa fa-plus-circle'></i> &nbsp;{{ __('pages/categories.create') }}</a>
            <p></p>
            <br>

        </div>
        <div class="panel-body">
            <categories-list-component :categories='@json($categories)' base-url="{{route('management.categories.index')}}"></categories-list-component>

        </div>
    </div>

@endsection
