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

                @foreach($transactions  as $transaction)
                    @if($transaction['is_transaction'])
                        <tr>
                            <th class="text-center ">{{$transaction->created_at}}</th>
                            <th class="text-center ">{{$transaction->container_id}}</th>
                            <th class="text-center ">
                                @if(!empty($transaction->user))
                                    <a href="{{ route('accounting.identities.show',$transaction->user->id) }}">
                                        {{ $transaction->user->locale_name}}
                                    </a>
                                @else
                                    -
                                @endif
                            </th>
                            <th class="text-center ">
                                @if($transaction->invoice_id>=1)
                                    @if(in_array($transaction->invoice->invoice_type,['sale','r_sale']))
                                        <a href="{{ route('accounting.sales.show',$transaction->invoice->id ) }}">
                                            {{$transaction->invoice->title  }}
                                        </a>
                                    @else
                                        <a href="{{ route('accounting.purchases.show',$transaction->invoice->id)}}">
                                            {{ $transaction->invoice->title }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('accounting.transactions.show', $transaction->container_id) }}">
                                        {{  $transaction->container_id }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center ">{{ money_format("%i",$transaction['debit_amount'])  }}</th>
                            <th class="text-center ">{{ money_format("%i",$transaction['credit_amount'])  }}</th>
                            <th class="text-center ">{{ money_format("%i", $transaction['total_debit_amount'])  }}</th>
                            <th class="text-center ">{{ money_format("%i",$transaction['total_credit_amount'])  }}</th>


                        </tr>
                    @else
                        @includeIf('accounting.charts.transactions.v2.index_account_loader',['transaction' => $transaction])
                    @endif
                @endforeach
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    </div>

@endsection