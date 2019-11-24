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
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">الرقم</th>
                    <th class="text-center ">الهوية</th>
                    <th class="text-center ">بيان</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">رصيد</th>
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
                                 <th class="text-center ">{{$transaction->id}}</th>
                                 <th class="text-center ">
                                     @if(!empty($transaction->user))
                                         <a href="{{ route('management.users.show',$transaction->user->id) }}">{{
                                        $transaction->user->name
                                        }}</a>
                                     @else
                                         -
                                     @endif
                                 </th>
                                 <th class="text-center ">
                                     @if($transaction->invoice_id>=1)
                                         @if(in_array($transaction->invoice->invoice_type,['sale','r_sale']))
                                             <a href="{{ route('management.sales.show',$transaction->invoice->id ) }}">{{
                               $transaction->invoice->title  }}</a>
                                         @else
                                             <a href="{{ route('management.purchases.show',$transaction->invoice->id)
                                               }}"> {{ $transaction->invoice->title }}</a>
                                         @endif


                                     @else
                                         <a href="{{ route('management.accounts.show', $transaction->debitable->id) }}">{{
                                                                         $transaction->debitable->locale_name }}</a>

                                     @endif
                                 </th>
                                 <th class="text-center ">0</th>
                                 <th class="text-center ">{{money_format("%i",$transaction->amount)  }}</th>
                                 <th class="text-center ">{{ money_format("%i",$total_balance)  }}</th>

                             </tr>
                        @else
					    <?php $total_balance = $total_balance + $transaction['amount'];?>
                             <tr>
                                 <th class="text-center ">{{$transaction->created_at}}</th>
                                 <th class="text-center ">{{$transaction->id}}</th>
                                 <th class="text-center ">
                                     @if(!empty($transaction->user))
                                         <a href="{{ route('management.users.show',$transaction->user->id) }}">{{ $transaction->user->name }}</a>
                                     @else
                                         -
                                     @endif
                                 </th>
                                 <th class="text-center ">
                                     @if($transaction->invoice_id>=1)
                                         @if(in_array($transaction->invoice->invoice_type,['sale','r_sale']))
                                             <a href="{{ route('management.sales.show',$transaction->invoice->id ) }}">{{
                               $transaction->invoice->title  }}</a>
                                         @else
                                             <a href="{{ route('management.purchases.show',$transaction->invoice->id)
                                               }}"> {{ $transaction->invoice->title }}</a>
                                         @endif


                                     @else
                                         <a href="{{ route('management.accounts.show', $transaction->creditable->id) }}">{{
                                                                         $transaction->creditable->locale_name }}</a>

                                     @endif
                                 </th>
                                 <th class="text-center ">{{ money_format("%i",$transaction->amount )}}</th>
                                 <th class="text-center ">0</th>
                                 <th class="text-center ">{{ money_format("%i",$total_balance)  }}</th>

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
                             <th class="text-center "><a href="{{ route('management.accounts.show',
                                $transaction->id) }}">{{
                                $transaction->locale_name }}</a></th>
                             <th class="text-center ">{{ money_format("%i",$transaction->debit )}}</th>
                             <th class="text-center ">{{ money_format("%i",$transaction->credit )}}</th>
                             <th class="text-center ">{{ money_format("%i",$total_balance)  }}</th>

                         </tr>

                    @endif

                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

