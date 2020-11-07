@extends('images_uploads.layout')

@section('title')
    المنتجات
@endsection
@section("content")
    <h1>{{$completedProducts}}</h1>
    <div class="text-center container" id="app">
        <products-list-for-uploading-images-component query-active-model="{{$activeModel}}" :completed-products="{{$completedProducts}}" :products='@json($items)'  :items-count="{{$itemsCount}}" :category-id='@json($categoryId)' :categories='@json($categories)'></products-list-for-uploading-images-component>
        {!! $links !!}
    </div>

@endsection