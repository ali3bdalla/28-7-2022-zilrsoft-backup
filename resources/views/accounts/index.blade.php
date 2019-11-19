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
            <a href="{{route('management.charts.create')}}" class="button is-primary  pull-right"><i
                        class='fa fa-plus-circle'></i> اضافة حساب</a>
            <p></p>
            <br>

        </div>
        <div class="panel-body">
            <accounting-chart-accounts-list-component :categories='@json($charts)' base-url="{{route
            ('management.charts.index')}}"></accounting-chart-accounts-list-component>

        </div>
    </div>

@endsection
