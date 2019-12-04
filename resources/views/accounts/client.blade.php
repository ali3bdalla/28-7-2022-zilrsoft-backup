@extends('layouts.master2')


@section('title',$account->locale_name)


{{--@section('translator')--}}
{{--    <script defer>--}}
{{--        window.translator = '@json(trans('pages/invoice'))'--}}
{{--    </script>--}}
{{--@stop--}}




@section('content')



    <div class="card">


        <div class="table-container">
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
                    <th class="text-center ">رقم</th>
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
                        <th class="text-center ">{{$transaction->id}}</th>
                        <th class="text-center ">
                            @if($transaction->invoice_id>=1)
                                <a href="{{  route('management.sales.show',
                        $transaction->invoice->sale->id)}}">{{
                        $transaction->invoice->title }}</a>

                            @else

                                <a href="{{  route('management.transactions.show',
                        $transaction->container_id)}}">{{
                        $transaction->container_id }}</a>

                            @endif
                        </th>
                        @if($transaction['creditable_type']=="")
						<?php $balance = $balance + $transaction['amount'];?>
                            <th class="text-center ">{{money_format("%i",$transaction->amount)}}</th>
                            <th class="text-center ">0</th>
                        @else
						<?php $balance = $balance - $transaction['amount'];?>
                            <th class="text-center ">0</th>
                            <th class="text-center ">{{money_format("%i",$transaction->amount)}}</th>
                        @endif
                        <th class="text-center ">
                            @if($balance>0)

                                {{money_format("%i",$balance)}}
                            @else
                                0
                            @endif
                        </th>
                        <th class="text-center ">
                            @if($balance<0)

                                {{money_format("%i",abs($balance))}}
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

