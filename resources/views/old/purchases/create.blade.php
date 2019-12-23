@extends('old.layouts.master2')


@section('title',__('pages/invoice.purchase'))
@section('desctipion','create purchase invoice')
@section('route',route('management.purchases.index'))


@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop



@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">

                <create-purchase-form-component2
                        :expenses='@json($expenses)'
                        :vendors='@json($vendors)'
                        :gateways='@json($gateways)'
                        :receivers='@json($receivers)'
                        :creator='@json(auth()->user()->with('department','branch')->first())'>
                </create-purchase-form-component2>

                {{--                <create-purchase-form-component--}}
                {{--                        :vendors='@json($vendors)'--}}
                {{--                        :gateways='@json($gateways)'--}}
                {{--                        :receivers='@json($receivers)'--}}
                {{--                        :creator='@json(auth()->user()->with('department','branch')->first())'>--}}
                {{--                </create-purchase-form-component>--}}

            </div>
        </div>
    </div>

@stop
