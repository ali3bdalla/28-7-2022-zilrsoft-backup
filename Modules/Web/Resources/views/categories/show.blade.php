@extends('web::layouts.master')

@section('content')


        @if($category->children()->count() != 0)
            @include('web::categories.layout.sliderCollection')
            @include('web::layouts.breacrumb',['page' => 'show-category'])
            @include('web::categories.layout.subCategoriesCollection')
            @include('web::items.layout.verticalScrollableCollection')
        @else
            @include('web::layouts.breacrumb',['page' => 'show-category'])
            <grid-items-collection-component :category-id="{{ $category->id }}">
            </grid-items-collection-component>
        @endif

@endsection
