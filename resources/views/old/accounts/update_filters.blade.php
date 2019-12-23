@extends('old.layouts.master2',['vuejs'=>true])


@section('title',$category->name  . ' - ' .__('pages/categories.filters'))
@section('desctipion','')
@section('route',route('management.categories.filters',$category->id))



@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/categories'))'
    </script>
@stop



@section('content')
    <div class="box">
        <category-filters-component :sys_filters='@json($all_filters)' :cat_filters='@json($cat_filters)'
                                    :category='@json($category)'></category-filters-component>
    </div>

@stop











@section("page_css")

@endsection








@section('page_js')

@endsection



