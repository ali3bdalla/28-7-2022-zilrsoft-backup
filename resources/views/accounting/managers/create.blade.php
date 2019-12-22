@extends('accounting.layout.master')

@section('title',__('pages/users.create_manager'))



@section("content")
    <accounting-managers-create-component

            :branches='@json($branches)'>

    </accounting-managers-create-component>
@endsection