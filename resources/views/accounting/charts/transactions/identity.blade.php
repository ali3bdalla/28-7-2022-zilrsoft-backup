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
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الرصيد</th>
                </tr>
                <tr>
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">رقم</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>

                @foreach($users as $user)
                    <tr>
                        <th class="text-center ">{{ $user['id'] }}</th>
                        @if($account->slug=='clients')
		                    <?php $client_transactions_amount = $user->cleintTransactionsAmount($account);?>
                            <th class="text-center "><a
                                        href="{{ route('accounting.accounts.client',[ $user['id'],$account->id] )
                                        }}">{{
                        $user['locale_name'] }}</a></th>


                            <th class="text-center ">{{ money_format("%i",$account->debit_transaction()->where('user_id',$user['id'])->sum('amount')) }}</th>
                            <th class="text-center ">{{ money_format("%i",$account->credit_transaction()->where('user_id', $user['id'])->sum('amount')) }}</th>
                            {{--                            <th class="text-center ">{{ money_format("%i",$user->credit_transaction()->sum('amount')) }}</th>--}}
                            <th class="text-center ">{{ $client_transactions_amount > 0  ? money_format("%i",
                            $client_transactions_amount) : 0 }}</th>

                            <th class="text-center ">{{ $client_transactions_amount < 0  ? money_format("%i",
                            abs($client_transactions_amount)) : 0 }}</th>

                        @else

						<?php $vendor_transactions_amount = $user->vendorTransactionsAmount($account);?>
                            <th class="text-center "><a
                                        href="{{ route('accounting.accounts.vendor',[ $user['id'],$account->id] ) }}">{{
                        $user['locale_name'] }}</a></th>
                            <th class="text-center ">{{ money_format("%i",$account->debit_transaction()->where('user_id',$user['id'])->sum('amount')) }}</th>
                            <th class="text-center ">{{ money_format("%i",$account->credit_transaction()->where('user_id', $user['id'])->sum('amount')) }}</th>
                            {{--                            <th class="text-center ">{{ money_format("%i",$user->credit_transaction()->sum('amount')) }}</th>--}}
                            <th class="text-center ">{{ $vendor_transactions_amount < 0  ? money_format("%i",
                            abs($vendor_transactions_amount)) : 0 }}</th>

                            <th class="text-center ">{{ $vendor_transactions_amount > 0  ? money_format("%i",
                            $vendor_transactions_amount) : 0 }}</th>

                        @endif


                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

