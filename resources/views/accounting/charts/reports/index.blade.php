@extends('accounting.layout.master')

@section('title','تقرير')



@section("before_content")

@endsection




@section("content")
    <accounting-account-report-component :accounts='@json($accounts)'></accounting-account-report-component>
@endsection





@section("after_content")
@endsection