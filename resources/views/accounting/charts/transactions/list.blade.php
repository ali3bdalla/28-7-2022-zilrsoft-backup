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
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الارصدة</th>
                </tr>
                <tr>
                    <th class="text-center "> التاريخ</th>
                    <th class="text-center "> رقم القيد</th>
                    <th class="text-center "> الهوية</th>
                    <th class="text-center ">البيان</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>
			 <?php
			 $total_credit = 0;
			 $total_debit = 0;

			 $total_balance = 0;
			 ?>
                @foreach($transactions as $transaction)

                    @if($transaction['is_transaction'])


                        @if($transaction['type']=='credit')

					    <?php $total_balance = $total_balance - $transaction['amount'];?>
                             <tr>
                                 <th class="text-center ">{{$transaction->created_at}}</th>
                                 <th class="text-center ">{{$transaction->container_id}}</th>
                                 <th class="text-center ">
                                     @if(!empty($transaction->user))
                                         <a href="{{ route('accounting.identities.show',$transaction->user->id) }}">{{
                                        $transaction->user->locale_name
                                        }}</a>
                                     @else
                                         -
                                     @endif
                                 </th>
                                 <th class="text-center ">
                                     @if($transaction->invoice_id>=1)
                                         @if(in_array($transaction->invoice->invoice_type,['sale','r_sale']))
                                             <a href="{{ route('accounting.sales.show',$transaction->invoice->id ) }}">{{
                               $transaction->invoice->title  }}</a>
                                         @else
                                             <a href="{{ route('accounting.purchases.show',
                                             $transaction->invoice->id)
                                               }}"> {{ $transaction->invoice->title }}</a>
                                         @endif


                                     @else
                                         {{--                                         <a href="{{ route('accounting.accounts.show', $transaction->debitable->id) }}">{{--}}
                                         {{--                                                                         $transaction->debitable->locale_name }}</a>--}}


                                         <a href="{{ route('accounting.transactions.show', $transaction->container_id)
                                         }}">{{
                                                                         $transaction->container_id }}</a>
                                     @endif
                                 </th>
                                 <th class="text-center ">0</th>
                                 <th class="text-center ">{{money_format("%i",$transaction->amount)  }}</th>
                                 @if($account->type=='debit')
                                     @if($total_balance<0)
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                     @else
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                     @endif
                                 @else

                                     @if($total_balance<0)
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>

                                     @else
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>


                                     @endif

                                 @endif

                             </tr>
                        @else
					    <?php $total_balance = $total_balance + $transaction['amount'];?>
                             <tr>
                                 <th class="text-center ">{{$transaction->created_at}}</th>
                                 <th class="text-center ">{{$transaction->id}}</th>
                                 <th class="text-center ">
                                     @if(!empty($transaction->user))
                                         <a href="{{ route('accounting.identities.show',$transaction->user->id) }}">{{ $transaction->user->locale_name }}</a>
                                     @else
                                         -
                                     @endif
                                 </th>
                                 <th class="text-center ">
                                     @if($transaction->invoice_id>=1)
                                         @if(in_array($transaction->invoice->invoice_type,['sale','r_sale']))
                                             <a href="{{ route('accounting.sales.show',
                                             $transaction->invoice->id )
                                             }}">{{
                               $transaction->invoice->title  }}</a>
                                         @else
                                             <a href="{{ route('accounting.purchases.show',
                                             $transaction->invoice->id)
                                               }}"> {{ $transaction->invoice->title }}</a>
                                         @endif


                                     @else
                                         <a href="{{ route('accounting.transactions.show', $transaction->container_id)
                                         }}">{{
                                                                         $transaction->container_id }}</a>

                                     @endif
                                 </th>
                                 <th class="text-center ">{{ money_format("%i",$transaction->amount )}}</th>
                                 <th class="text-center ">0</th>
                                 @if($account->type=='debit')
                                     @if($total_balance<0)
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                     @else
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                     @endif
                                 @else

                                     @if($total_balance<0)
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                     @else
                                         <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                         <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>

                                     @endif

                                 @endif

                             </tr>
                        @endif





                    @else

					<?php $total_balance = $total_balance + +$transaction['debit'] - $transaction['credit'];?>
                         <tr>
                             <th class="text-center ">{{$transaction->created_at}}</th>
                             <th class="text-center ">{{$transaction->id}}</th>
                             <th class="text-center ">
                                 -
                             </th>
                             <th class="text-center "><a href="{{ route('accounting.accounts.show',
                                $transaction->id) }}">{{
                                $transaction->locale_name }}</a></th>
                             <th class="text-center ">{{ money_format("%i",$transaction->debit )}}</th>
                             <th class="text-center ">{{ money_format("%i",$transaction->credit )}}</th>
                             @if($account->type=='debit')
                                 @if($total_balance<0)
                                     <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                     <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                 @else
                                     <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                     <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                 @endif
                             @else

                                 @if($total_balance<0)
                                     <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>
                                     <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                 @else
                                     <th class="text-center ">{{ money_format("%i",0)  }}</th>
                                     <th class="text-center ">{{ money_format("%i",abs($total_balance))  }}</th>

                                 @endif

                             @endif

                         </tr>

                    @endif

                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

