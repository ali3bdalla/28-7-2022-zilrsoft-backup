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

        <div class="panel-heading">

        </div>

        <div class="panel-body">
            @if(!empty($transactions))
                <table class="table table-bordered table-bordered" style="direction: ltr">

                    <thead>
                    <th>الدائن</th>
                    <th>المدين</th>
                    <th>اسم الحساب</th>
                    <th> شرح</th>
                    <th>رقم القيد</th>
                    <th>التاريخ</th>
                    </thead>


                    @foreach($transactions as $transaction)
                        <tbody>

                        @if($transaction['invoice_id']>=1 && !empty($transaction->invoice))

                            @if(!empty($transaction->invoice->sale))
						   <?php $sale = $transaction->invoice->sale;$invoice_transactions =
							   $transaction->invoice->transactions()->where('description','!=','client_balance')->get
							   ();?>
                                 @includeIf('accounting.transactions.sale')

                            @elseif(!empty($transaction->invoice->purchase))
						   <?php $purchase = $transaction->invoice->purchase;$invoice_transactions =
							   $transaction->invoice->transactions()->where('description','!=','vendor_balance')->get
							   ();?>
                                 @includeIf('accounting.transactions.purchase')
                            @endif

                        @else

                            @foreach($transaction->transactions as $index => $real_transaction)
                                <tr style="border:none">


                                    @if($real_transaction['debitable_type']!="")
                                        <th></th>
                                        <th>{{ $real_transaction['amount'] }}</th>
                                        <th>@if(!empty($real_transaction->debitable)) {{ $real_transaction->debitable->locale_name }} @endif </th>

                                    @else
                                        <th>{{ $real_transaction['amount'] }}</th>
                                        <th></th>
                                        <th>@if(!empty($real_transaction->creditable)) {{ $real_transaction->creditable->locale_name }} @endif </th>
                                    @endif


                                    @if($index==0)

                                        <th width="20%" style="vertical-align: inherit;" rowspan="{{ count
                                ($transaction->transactions) }}"> {{
                                $transaction['description']
                            }}</th>
                                        <th width="10%" style="vertical-align: inherit;"
                                            rowspan="{{ count($transaction->transactions) }}"><a href="{{ route('accounting.transactions.show',$transaction->id)
                                    }}">{{
                                $transaction['id'] }}</a></th>
                                        <th width="15%" style="vertical-align: inherit;" class="datedirection" rowspan="{{
                                count
                                ($transaction->transactions) }}">{{
                                $transaction['created_at'] }}</th>
                                    @endif
                                </tr>
                            @endforeach


                            <tr>
                                <th>{{ $transaction['amount'] }}</th>
                                <th>{{ $transaction['amount'] }}</th>
                                <th></th>
                                <th></th>
                                <th></th>

                            </tr>
                        </tbody>

                        @endif


                    @endforeach


                </table>


                {{$transactions->links()}}
            @endif
        </div>
    </div>


@stop
