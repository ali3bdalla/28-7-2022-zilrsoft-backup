@extends('accounting.layout.master')

@section('title',__('pages/items.create'))



@section("before_content")

@endsection




@section("content")

    <accounting-items-create-component
            :can-create-category="{{ auth()->user()->canDo('create category') }}"
            :can-create-filter="{{ auth()->user()->canDo('create filter') }}"
            :can-edit-filter="{{ auth()->user()->canDo('update filter') }}"
            :vendors='@json($vendors)'
            :categories='@json($categories)'
            :is-cloned="false"
    >
    </accounting-items-create-component>
@endsection





@section("after_content")
@endsection