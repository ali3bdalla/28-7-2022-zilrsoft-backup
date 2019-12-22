@extends('accounting.layout.master')

@section('title',__('pages/categories.filters') . ' | ' .  $category->locale_name)



@section("before_content")

@endsection




@section("content")
    <accounting-category-filters-list-component
            :sys_filters='@json($all_filters)'
            :cat_filters='@json($cat_filters)'
            :can-create-filter='{{ auth()->user()->canDo('create filter') }}'
            :category='@json($category)'></accounting-category-filters-list-component>

@endsection