@extends('accounting.layout.master')


@section('title')
    {{ $account->locale_name }}
    @if($item)
         | {{  $item->locale_name }}
    @endif

    @if($user)
        | {{  $user->locale_name }}
    @endif

@endsection




@section('content')

    <accounting-global-transactions-list-component
            :account='@json($account)'
            :user='@json($user)'
            :item='@json($item)'
    >

    </accounting-global-transactions-list-component>

@endsection