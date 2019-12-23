@extends('layouts.master2')


@section('title','payments methods')
@section('desctipion','create purchase invoice')
@section('route',route('management.sales.index'))


@section('content')
    <div class="box text-right">

        <a class="button is-info" href="{{route('management.gateways.create')}}"><i class="fa
        fa-plus-circle"></i>&nbsp;اضافة وسيلة دفع اخرى</a>

    </div>
    @empty(!$payments_methods)
        @foreach($payments_methods->chunk(2) as $methods)
            <div class="columns">
                @foreach($methods as $method)
                    <div class="column">
                        <div class="box text-center">
                            <p class="title">{{$method->name}}</p>
                            <p class="subtitle">{{$method->ar_name}}</p>
                            <p class="subtitle">{{$method->created_at}}</p>
                            <div class="box-footer">
                                <delete-button-component href="{{route('management.gateways.remove',$method->id)}}"
                                                         class='button is-danger'>
                                    <i class="fa fa-trash"></i>
                                    &nbsp;delete
                                </delete-button-component>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endforeach
    @endempty

@stop
