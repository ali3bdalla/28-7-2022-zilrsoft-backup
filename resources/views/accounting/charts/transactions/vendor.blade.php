@extends('accounting.layout.master')


@section('title',$account->locale_name)



@section('content')




    <div class="panel">

        <div class="panel-heading">

        </div>

        <div class="panel-body">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center ">التاريخ</th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">الرصيد</th>
                </tr>
                <tr>
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">رقم القيد</th>
                    <th class="text-center ">السند</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>


			 <?php $balance = 0;?>
                @foreach($transactions as $transaction)

                    <tr>
                        <th class="text-center ">{{$transaction->created_at}}</th>
                        <th class="text-center "><a href="{{ route('accounting.transactions.show',
                        $transaction->container_id)
                        }}">{{$transaction->container_id}}</a></th>
                        <th class="text-center ">@if(!empty($transaction->invoice))<a href="{{  route('accounting.purchases.show',
                        $transaction->invoice->id)}}">{{
                        $transaction->invoice->title }}</a>@endif</th>
                        @if($transaction['creditable_type']=="")

						<?php $balance = $balance + $transaction['amount'];?>
                            <th class="text-center ">{{moneyFormatter($transaction->amount)}}</th>
                            <th class="text-center ">0</th>

                        @else
						<?php $balance = $balance - $transaction['amount'];?>
                            <th class="text-center ">0</th>
                            <th class="text-center ">{{moneyFormatter($transaction->amount)}}</th>


                        @endif
                        <th class="text-center ">
                            @if($balance>0)

                                {{moneyFormatter(abs($balance))}}
                            @else
                                0
                            @endif
                        </th>
                        <th class="text-center ">
                            @if($balance<0)

                                {{moneyFormatter(abs($balance))}}
                            @else
                                0
                            @endif

                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

