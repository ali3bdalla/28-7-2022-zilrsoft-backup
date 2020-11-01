@extends('images_uploads.layout')

@section('title')
    المنتجات
@endsection
@section("content")
    <div class="text-center container" id="app">
        <products-list-for-uploading-images-component :products='@json($items)'  :items-count="{{$itemsCount}}" :category-id='@json($categoryId)' :categories='@json($categories)'></products-list-for-uploading-images-component>
        {{ $items->appends(['category_id' => $categoryId])->links() }}
    </div>

@endsection