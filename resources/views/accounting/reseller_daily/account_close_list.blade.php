@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')

    <a class="btn btn-custom-primary" href="{{ route('daily.reseller.closing_accounts.create') }}">انشاء اقفال</a>
    <a class="btn btn-custom-primary" href="{{ route('daily.reseller.accounts_transactions.index') }}">التحويلات
    </a>

@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>رقم العملية</th>
                    <th>من</th>
                    <th>الى</th>
                    <th>المبلغ المفترض</th>
                    <th>المبلغ الفعلي</th>
                    <th>الفرق</th>
                </tr>
                </thead>

                @foreach($managerCloseAccountList as $transaction)
                    <tbody>
                    {{--                    @if($transaction['transaction_type']=='close_account')--}}
                    <tr class="">
                        <td>CSH-{{ $transaction->id }}</td>
                        <td>{{ $transaction->from }}</td>
                        <td>{{ $transaction->to }}</td>
                        <td>{{ money_format("%i",$transaction->amount) }}</td>
                        @if($transaction->shortage_amount<0)
                            <td>{{ money_format("%i",($transaction->amount - abs($transaction->shortage_amount ))) }}</td>

                        @else
                            <td>{{ money_format("%i",($transaction->amount + abs( $transaction->shortage_amount )))}}</td>

                        @endif
                        <td>
                            @if($transaction->shortage_amount>0)
                                <span class="text-green">{{ money_format("%i",$transaction->shortage_amount) }}</span>
                            @elseif($transaction->shortage_amount<0)
                                <span class="text-danger">{{ money_format("%i",$transaction->shortage_amount) }}</span>
                            @else
                                {{ money_format("%i",$transaction->shortage_amount) }}

                            @endif
                        </td>
                    </tr>

                    {{--                    @else--}}

                    {{--                        <tr>--}}
                    {{--                            <td>تحويل</td>--}}
                    {{--                            <td>{{ $transaction->creator->locale_name }}</td>--}}
                    {{--                            <td>{{ $transaction->receiver->locale_name }}</td>--}}
                    {{--                            <td>{{ money_format("%i",$transaction->amount) }}</td>--}}
                    {{--                            <td>--}}
                    {{--                                @if($transaction->is_pending)--}}
                    {{--                                    منتظرة--}}
                    {{--                                @else--}}
                    {{--                                    مقبولة--}}
                    {{--                                @endif--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                    @endif--}}
                    {{--                    @foreach($transaction->container->transactions as $transaction)--}}

                    {{--                        @if($transaction->amount>0)--}}
                    {{--                            @if($transaction->debitable_type=="")--}}
                    {{--                                <tr>--}}
                    {{--                                    <td>{{ $transaction->creditable->locale_name }} </td>--}}
                    {{--                                    <td>-</td>--}}
                    {{--                                    <td>-</td>--}}
                    {{--                                    <td>-{{ $transaction->amount }}</td>--}}
                    {{--                                    <td></td>--}}
                    {{--                                </tr>--}}
                    {{--                            @else--}}
                    {{--                                <tr>--}}
                    {{--                                    <td>{{ $transaction->debitable->locale_name }} </td>--}}
                    {{--                                    <td>-</td>--}}
                    {{--                                    <td>-</td>--}}
                    {{--                                    <td>+{{ $transaction->amount }}</td>--}}
                    {{--                                    <td></td>--}}
                    {{--                                </tr>--}}
                    {{--                            @endif--}}

                    {{--                        @endif--}}

                    {{--                    @endforeach--}}

                    {{--                    --}}
                    {{--                    @if($transaction['transaction_type']=='close_account')--}}
                    {{--                        <tr>--}}
                    {{--                            <td>تقفيل الحساب</td>--}}
                    {{--                            <td>{{ $transaction->close_account_start_date }}</td>--}}
                    {{--                            <td>{{ $transaction->close_account_end_date }}</td>--}}
                    {{--                            <td>{{ money_format("%i",$transaction->amount) }}</td>--}}
                    {{--                            <td>{{ money_format("%i",$transaction->shortage_amount) }}</td>--}}
                    {{--                        </tr>--}}
                    {{--                    @else--}}
                    {{--                        --}}{{----}}
                    {{--                        <tr>--}}
                    {{--                            <td>تحويل</td>--}}
                    {{--                            <td>{{ $transaction->creator->locale_name }}</td>--}}
                    {{--                            <td>{{ $transaction->receiver->locale_name }}</td>--}}
                    {{--                            <td>{{ money_format("%i",$transaction->amount) }}</td>--}}
                    {{--                            <td>--}}
                    {{--                                @if($transaction->is_pending)--}}
                    {{--                                    منتظرة--}}
                    {{--                                @else--}}
                    {{--                                    مقبولة--}}
                    {{--                                @endif--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                    @endif--}}
                    </tbody>
                @endforeach

                {{ $managerCloseAccountList->links() }}
            </table>
        </div>
    </div>
@endsection