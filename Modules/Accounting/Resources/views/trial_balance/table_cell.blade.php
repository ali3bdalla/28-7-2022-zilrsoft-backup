@php
    $padding+=20
@endphp


@if($account->children()->count() === 0)
    @php
        /** @var TYPE_NAME $account */
        $result =  $account->getTrialBalanceData();
       $totalCreditAmount= $totalCreditAmount  + (float)$result['debit_amount'];
       $totalDebitAmount = $totalCreditAmount + (float)$result['credit_amount'];
       $totalCreditBalance =$totalCreditBalance +  (float)$result['debit_balance'];
       $totalDebitBalance= $totalDebitBalance + (float)$result['credit_balance'];

    @endphp
    {{--    @if($result['debit_amount'] > 0  || $result['credit_amount'] > 0 )--}}
    {{--        <tbody>--}}
    <tr>
{{--        <td>   {{ $account->id }}</td>--}}
        <td><a target="_blank" href="{{ route('accounting.accounts.show',$account->id) }}">{{ $account->locale_name }}</a></td>
        <td>{{ money_format('%i',$result['debit_amount']) }}</td>
        <td>{{ money_format('%i',$result['credit_amount'])}}</td>
        <td>{{ money_format('%i',$result['debit_balance'])}}</td>
        <td>{{ money_format('%i',$result['credit_balance'])}}</td>
    </tr>
    {{--    @endif--}}
@else
    @foreach($account->children as $account2)
        <tbody style="margin-right: 5px !important;" class="table-body-child">
        @if($account2->children()->count() > 0)
            <tr>
{{--                <td></td>--}}
                <td colspan="" class="text-bold" style="padding-right:{{ $padding }}px;text-align: right !important;">{{$account2->locale_name}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
        @endif
        @includeIf('accounting::trial_balance.table_cell',['account' => $account2])
        </tbody>
    @endforeach
@endif






{{--{{  $totalCreditAmount }}--}}