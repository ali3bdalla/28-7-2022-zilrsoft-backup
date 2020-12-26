@extends('accounting.layout.master')

@section('title',__('sidebar.adjust_stock'))





@section("content")

    <accounting-adjust-stock-create-component
    :user='@json($user)'
    :creator='@json($creator)'
    :can-create-item="{{ auth()->user()->canDo('create item') }}"
    :can-view-items="{{ auth()->user()->canDo('view item')  }}"
    >


    </accounting-adjust-stock-create-component>
@endsection





@section("after_content")
@endsection