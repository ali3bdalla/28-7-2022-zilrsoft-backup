@extends('accounting.layout.master')


@section('title',$account->locale_name)




@section('content')

    <accounting-global-transactions-list-component
            :account='@json($account)'
            :user='@json($user)'
            >

    </accounting-global-transactions-list-component>

@endsection