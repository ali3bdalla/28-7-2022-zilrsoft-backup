@extends('layouts.master2')


@section('title')
    @if($type=='all')
        {{ __('sidebar.vouchers') }}
    @elseif($type=='receipt')
        {{ __('pages/payments.receipts') }}
    @else
        {{ __('pages/payments.payments') }}
    @endif

@stop
@section('desctipion',__('pages/payments.description'))
@section('route',route('management.payments.index'))


@section('content')



    <div class="box">
        <div class="card-body">
            <div class="pull-right">
                @if($type=='all')
                    <a href="{{route('management.payments.create_payment')}}" class="button is-info "><i class='fa
            fa-plus-circle'></i>&nbsp; {{ __('pages/payments.create_payment') }}</a>
                    &nbsp;&nbsp;
                    <a href="{{route('management.payments.create_receipt')}}" class="button is-warning "><i class='fa
            fa-plus-circle'></i>&nbsp; {{ __('pages/payments.create_receipt') }}</a>

                @elseif($type=='receipt')

                    <a href="{{route('management.payments.create_receipt')}}" class="button is-warning "><i class='fa
            fa-plus-circle'></i>&nbsp; {{ __('pages/payments.create_receipt') }}</a>

                @else

                    <a href="{{route('management.payments.create_payment')}}" class="button is-info "><i class='fa
            fa-plus-circle'></i>&nbsp; {{ __('pages/payments.create_payment') }}</a>

                @endif
            </div>


            <br>
            <br>


            {{--            <span class="subtitle">--}}
            {{--                @if($type=='all')--}}
            {{--                    {{ __('sidebar.payments') }}--}}
            {{--                @elseif($type=='vouchers')--}}
            {{--                    {{ __('pages/payments.receipts') }}--}}
            {{--                @else--}}
            {{--                    {{ __('pages/payments.payments') }}--}}
            {{--                @endif--}}
            {{--            </span>--}}

        </div>


    </div>
    <div class="box">
        @if(isset($payments))
            <table class="table is-borderd table-bordered text-center">
                <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>{{ __('pages/payments.user_name') }}</th>
                    <th>{{ __('reusable.date') }}</th>
                    <th>{{ __('reusable.creator') }}</th>
                    <th>{{ __('pages/payments.total_amount') }}</th>
                    <th>{{ __('pages/payments.gateway') }}</th>
                    <th>{{ __('reusable.manage') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{$payment->id}}</td>
                        <td>{{$payment->user->name}}</td>
                        <td class="datedirection">{{$payment->created_at}}</td>
                        <td>{{$payment->creator->name}} </td>

                        <td>{{$payment->amount}}</td>
                        <td>{{$payment->account->locale_name}}</td>
                        <td>


                            <div class="dropdown">
                                <button class="button is-primary  btn-xs" type="button" id="optionsMenu{{
                                    $payment->id}}"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> &nbsp; {{ __('reusable.manage') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $payment->id}}">
                                    <a class="dropdown-item"
                                       href="{{route('management.payments.show',$payment->id)}}"><i
                                                class="fa fa-eye"></i> &nbsp; {{ __('reusable.view') }}</a>
                                    <a class="dropdown-item" href="{{route('management.payments.edit',$payment->id)
                                    }}"><i class="fa fa-print"></i> {{ __('reusable.print') }}</a>

                                </div>
                            </div>


                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $payments->links()}}
        @endif

    </div>

@stop
