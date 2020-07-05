@extends('web::layouts.master')

@section('content')

        @include('web::categories.layout.sliderCollection')
        @include('web::layouts.breacrumb',['page' => 'show-category'])


        @if($category->parent_id == 0)
            @include('web::categories.layout.subCategoriesCollection')
            @include('web::items.layout.verticalScrollableCollection')
        @else
            <grid-items-collection-component :category-id="{{ $category->id }}">
            </grid-items-collection-component>
        @endif

@endsection
