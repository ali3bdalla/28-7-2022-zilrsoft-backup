@extends('accounting.layout.master')

@section('title',__('sidebar.categories'))
@section('buttons')
    @can("create category")
        <a href="{{route('accounting.categories.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/categories.create') }}
        </a>
    @endcan
@stop




@section("content")
    @foreach($categories as $category)
        <accounting-treeview-raw-layout-component
                :can-create="{{auth()->user()->canDo('create category')}}"
                :can-edit="{{auth()->user()->canDo('edit category')}}"
                :can-delete="{{auth()->user()->canDo('delete category')}}"
                :can-view="{{auth()->user()->canDo('view category')}}"
                :item='@json($category)'
                :key="{{ $category->id }}">
        </accounting-treeview-raw-layout-component>
    @endforeach
@endsection



