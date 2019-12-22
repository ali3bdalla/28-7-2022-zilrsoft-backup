@extends('accounting.layout.master')

@section('title',__('pages/users.create'))



@section("before_content")

@endsection




@section("content")
    <accounting-identities-create-component
            :banks='@json($banks)'
            :editing-identity="false"
    ></accounting-identities-create-component>
@endsection





@section("after_content")
@endsection