@extends('accounting.layout.master')


@section('title', __('sidebar.items'))
@section('desctipion',__('pages/items.description'))
@section('route',route('management.items.index'))

@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/items'))`
    </script>
@stop
@section('page_css')
    <style type="text/css">
        th {
            text-align: center !important;
        }
    </style>
@endsection

@section('content')
    {{--	<div class="message is-info">--}}

    <div class="box">


        <div class="card-body">
            <a href="{{route('management.items.create')}}" class="button is-info pull-right">
                <i class='fa  fa-plus-circle'></i>&nbsp; {{ __('pages/items.create') }}</a>
            <span class="subtitle"></span>

            <br>
        </div>
    </div>
    <div class="card">
        @if(isset($_GET['selectable']))


            @if(isset($_GET['is_purchase']))
                <item-list-table-component
                        :linkable="1"
                        :is_ask_for_purchase="1"
                        load-type="{{ $loadType }}"
                        :creators="{{$creators}}"></item-list-table-component>

            @else
                <item-list-table-component
                        :linkable="1"
                        :is_ask_for_purchase="0"
                        load-type="{{ $loadType }}"
                        :creators="{{$creators}}"></item-list-table-component>

            @endif

        @else
            <item-list-table-component
                    :linkable="0"
                    load-type="{{ $loadType }}"
                    :creators="{{$creators}}"></item-list-table-component>
        @endif

    </div>
    {{--	</div>--}}



@stop


@section('page_js')

@stop
