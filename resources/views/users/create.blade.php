@extends('layouts.master2')


@section('title','users')
@section('desctipion','create user')
@section('route',route('management.users.index'))

@section('content')


<div class="box">
    <create-user-form-component :branchs='@json($branchs)' :gateways='@json($gateways)'></create-user-form-component>
</div>


@stop
