@extends('accounting::layouts.master')

@section('content')

    <div class="table">
        <table class="table table-bordered bg-white" id="myTable" class="display" style="width:100%">
            <thead>
            <tr>
                <td></td>
                <td colspan="2">المجاميع</td>
                <td colspan="2">الارصدة</td>

            </tr>
            <tr>
                <td>اسم الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>مدين</td>
                <td>دائن</td>

            </tr>
            </thead>

            @foreach($accounts as $account)

                <tbody>
                <tr>

                    <td class="text-bold text-right p-3" style="text-align: right !important;font-size: 25px">{{$account->locale_name}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                @foreach($account['mainAccountChildren'] as $account2)
                    <tbody style="margin-right: 5px !important;" class="table-body-child">
                    <tr>
                        <td colspan="" class="text-bold"
                            style="padding-right:40px;text-align: right !important;"><a href="{{ route('accounting.accounts.show',$account2->id) }}">{{$account2->locale_name}}</a> </td>
                        <td>{{ $account2['debit_amount'] }}</td>
                        <td>{{$account2['credit_amount']}}</td>
                        <td>{{$account2['debit_balance']}}</td>
                        <td>{{$account2['credit_balance']}}</td>

                    </tr>
                    </tbody>
                    @endforeach


                    </tbody>
                @endforeach
                <thead style="background-color:black;color:white">
                <tr>
                    <th></th>
                    <th>{{ $totalDebitAmount}}</th>
                    <th>{{ $totalCreditAmount}}</th>
                    <th>{{ $totalDebitBalance}}</th>
                    <th>{{ $totalCreditBalance}}</th>
                </tr>

{{--                <tr>--}}
{{--                    <th></th>--}}
{{--                    <th colspan="2">{{ money_format('%i',$totalDebitAmount - $totalCreditAmount)}}</th>--}}


{{--                    <th colspan="2">{{ money_format('%i',$totalDebitBalance - $totalCreditBalance)}}</th>--}}

{{--                </tr>--}}

                </thead>
        </table>
    </div>

@endsection


{{--@section('page_css')--}}
{{--    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">--}}
{{--    <script src="https://code.jquery.com/jquery-3.5.1.js" defer></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>--}}
{{--@endsection--}}


{{--@section('page_js')--}}

{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            $('#myTable').DataTable({--}}

{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}