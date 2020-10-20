@extends('accounting.layout.master')


@section('title',__('sidebar.transactions'))


@section('page_css')
    <style>
        th {
            text-align: center !important;
        }

        .dropdown-menu {
            transform: translate3d(54px, 41px, 0px) !important;
        }
    </style>
@stop


@section('buttons')

    @can('create transaction')
        <a href="{{ route("accounting.transactions.create") }}" class="btn btn-custom-primary"><i class='fa
                    fa-plus-circle'></i>&nbsp; {{ __('pages/transactions.create') }}</a>
        <br>
    @endcan
@endsection
@section('content')
    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered table-bordered" style="">
                <thead>
                <th>التاريخ</th>
                <th>رقم القيد</th>
                <th> شرح</th>
                {{--                <th>اسم الحساب</th>--}}
                <th>المدين</th>
                <th>الدائن</th>
{{--                <th>المدين</th>--}}
{{--                <th>الدائن</th>--}}
                </thead>
                <body>
                @foreach($transactions as $transaction)
                    {{--                    @php--}}
                    {{--                        $totalCredit = 0;--}}
                    {{--                        $totalDebit = 0;--}}
                    {{--                    @endphp--}}
                    {{--                    @foreach($entity->transactions as $index => $transaction)--}}
                    {{--                        <tr style="border:none">--}}
                    {{--                            <th>{{  $entity->created_at }}</th>--}}
                    {{--                            <th>{{ $entity->id }}</th>--}}
                    {{--                            <th>{{  $entity->description }}</th>--}}
                    {{--                            <th>{{ $transaction->account_name }}</th>--}}

                    {{--                            @if($transaction->type=="credit")--}}
                    {{--                                @php--}}
                    {{--                                    $totalCredit+= (float)$transaction['amount'];--}}
                    {{--                                @endphp--}}
                    {{--                                <th></th>--}}
                    {{--                                <th>{{ $transaction['amount'] }}</th>--}}
                    {{--                            @else--}}
                    {{--                                @php--}}
                    {{--                                    $totalDebit+= (float)$transaction['amount'];--}}
                    {{--                                @endphp--}}
                    {{--                                <th>{{ $transaction['amount'] }}</th>--}}
                    {{--                                <th></th>--}}
                    {{--                            @endif--}}


                    {{--                        </tr>--}}
                    {{--                    @endforeach--}}
                    <tr>

                        <th>{{ $transaction->created_at }}</th>
                        <th>{{ $transaction->container_id }}</th>
                        <th>

                            @if($transaction->invoice_id != null)
                                {{ $transaction->invoice->invoice_number }}
                            @else
                                {{ $transaction->description }}
                            @endif
                        </th>

                        <th> @if($transaction->type =='debit'){{ roundMoney($transaction->amount) }}@else 0 @endif</th>
                        <th> @if($transaction->type =='credit'){{ roundMoney($transaction->amount) }}@else 0 @endif</th>

{{--                        <th>{{  roundMoney($transaction->total_debit_amount) }}</th>--}}
{{--                        <th>{{  roundMoney($transaction->total_credit_amount) }}</th>--}}
                    </tr>
                @endforeach
                </body>
            </table>
            {{$transactions->links()}}
        </div>
    </div>
@stop
