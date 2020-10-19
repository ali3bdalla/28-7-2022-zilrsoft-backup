@extends('accounting.layout.master')


@section('title', __('sidebar.chart_of_accounts'))

@section('page_css')
@endsection

@section('buttons')
    @can("create chart")
        <a class="btn btn-custom-primary" href="{{ route('accounts.create') }}">اضافة حساب</a>
    @endcan
@endsection
@section('content')
    <accounting-chart-of-accounts-list-component
            :can-edit-account='@json(auth()->user()->can('edit chart'))'
            :accounts='@json($accounts)'></accounting-chart-of-accounts-list-component>
@endsection