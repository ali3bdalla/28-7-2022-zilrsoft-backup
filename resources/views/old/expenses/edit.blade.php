@extends("old.layouts.master2")


@section('title',$filter->locale_name)
@section('desctipion','')
@section('route',route('management.filters.index'))


@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/filters'))'
    </script>
@stop



@section("content")


    <div class="box">

        <filter-values-list-component :obj_filter='@json($filter)'
                                      :values_list='@json($filter->values()->with ('creator')->get())'>
        </filter-values-list-component>
    </div>





@endsection
