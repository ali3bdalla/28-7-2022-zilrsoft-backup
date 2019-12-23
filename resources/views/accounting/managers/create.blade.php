@extends('accounting.layout.master')

@section('title',__('pages/users.create_manager'))



@section("content")
    <accounting-managers-create-component
            :editing-manager="false"
            :branches='@json($branches)'>

    </accounting-managers-create-component>
@endsection