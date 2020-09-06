@extends('accounting::layouts.master')

@section('content')
    @php
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        $totalCreditBalance = 0;
        $totalDebitBalance = 0
    @endphp
    <div class="table">
        <table class="table table-bordered bg-white" id="myTable" class="display" style="width:100%">
            <thead>
            <tr>
                <td></td>
                <td></td>
                <td colspan="2">المجاميع</td>
                <td colspan="2">الارصدة</td>

            </tr>
            <tr>
                <td>#</td>
                <td>اسم الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>مدين</td>
                <td>دائن</td>

            </tr>
            </thead>

            @php
                $padding = 20
            @endphp
            @foreach($accounts as $account)

                <tbody>
                <tr>
                    <td></td>

                    <td class="text-bold text-right p-3" style="text-align: right !important;font-size: 25px">{{$account->locale_name}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                @includeIf('accounting::trial_balance.table_cell',['account' => $account])

                </tbody>
            @endforeach
            <thead style="background-color:black;color:white">
            <tr>
                <th></th>
                <th></th>
                <th>{{ money_format('%i',$totalDebitAmount) }}</th>
                <th>{{ money_format('%i',$totalCreditAmount)}}</th>
                <th>{{ money_format('%i',$totalDebitBalance)}}</th>
                <th>{{ money_format('%i',$totalCreditBalance)}}</th>
            </tr>

            <tr>
                <th></th>
                <th></th>
                <th colspan="2">{{ money_format('%i',$totalDebitAmount - $totalCreditAmount)}}</th>


                <th colspan="2">{{ money_format('%i',$totalDebitBalance - $totalCreditBalance)}}</th>

            </tr>

            </thead>
        </table>
    </div>

@endsection


@section('page_css')
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
@endsection



@section('page_js')

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'colvis',
                    'excel',
                    'print'
                ]
            });
        });
    </script>
@endsection