@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')
    <a class="btn btn-custom-primary" href="{{ route('accounting.reseller_daily.transfer_amounts') }}">انشاء تحويل</a>
    <a class="btn btn-custom-primary" href="{{ route('accounting.reseller_daily.account_close_list') }}">الاقفالات
    </a>



@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>رقم العملية </th>
                    <th>التاريخ</th>
                    <th>من</th>
                    <th>الى</th>
                    <th>القيمة</th>
                    <th>الحالة</th>
                </tr>
                </thead>

                @foreach($managerCloseAccountList as $transaction)
                    <tbody>
                    {{--                    @if($transaction['transaction_type']=='close_account')--}}
                    {{--                    <tr class="warning">--}}
                    {{--                        <td>تقفيل الحساب</td>--}}
                    {{--                        <td>{{ $transaction->close_account_start_date }}</td>--}}
                    {{--                        <td>{{ $transaction->close_account_end_date }}</td>--}}
                    {{--                        <td>{{ money_format("%i",$transaction->amount) }}</td>--}}
                    {{--                        <td>{{ money_format("%i",$transaction->shortage_amount) }}</td>--}}
                    {{--                    </tr>--}}

                    {{--                    @else--}}

                    <tr class="warning">
                        <td>TRANS-{{ $transaction->creator->id }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->receiver->locale_name }}</td>
                        <td>{{ money_format("%i",$transaction->amount) }}</td>
                        <td>
                            @if($transaction->is_pending)
                                منتظرة
                            @else
                                مقبولة
                            @endif
                        </td>
                    </tr>
                    {{--                    @endif--}}
                    @foreach($transaction->container->transactions as $transaction)

                        @if($transaction->amount>0)
                            @if($transaction->debitable_type=="")
                                <tr>
                                    <td>{{ $transaction->creditable->locale_name }} </td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-{{ $transaction->amount }}</td>
                                    <td></td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $transaction->debitable->locale_name }} </td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>+{{ $transaction->amount }}</td>
                                    <td></td>
                                </tr>
                            @endif

                        @endif

                    @endforeach

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