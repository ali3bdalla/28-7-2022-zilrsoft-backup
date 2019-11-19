@extends('layouts.master2')




@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/categories'))'
    </script>
@stop


@section('title', __('sidebar.chart_of_accounts'))
@section('desctipion',__('sidebar.categories'))
@section('route',route('management.accounts.index'))


@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{route('management.accounts.create')}}" class="button is-primary  pull-right"><i
                        class='fa fa-plus-circle'></i> اضافة حساب</a>
            <p></p>
            <br>

        </div>
        <div class="panel-body">
            <chart-of-accounts-component :accounts='@json($accounts)' base-url="{{route('management.accounts.index')
            }}"></chart-of-accounts-component>

        </div>
    </div>

@endsection
