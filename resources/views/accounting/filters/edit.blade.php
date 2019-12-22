@extends('accounting.layout.master')

@section('title',__('pages/filters.edit') . ' | ' . $filter->locale_name)



@section("before_content")

@endsection




@section("content")
    <accounting-edit-filter-and-values-component
            :filter='@json($filter)'
    >
    </accounting-edit-filter-and-values-component>

@endsection