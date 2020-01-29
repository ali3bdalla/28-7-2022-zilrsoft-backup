@extends('accounting.layout.master')

@section('title',__('pages/invoice.create'))





@section("content")

    <accounting-beginning-create-component
    :user='@json($user)'
    :creator='@json($creator)'
    :can-create-item="{{ auth()->user()->canDo('create item') }}"
    :can-view-items="{{ auth()->user()->canDo('view item')  }}"
    >


    </accounting-beginning-create-component>
@endsection





@section("after_content")
@endsection