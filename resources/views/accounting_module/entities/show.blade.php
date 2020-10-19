@extends('accounting.layout.master')


@section('title',"قيد  - {$entity->id}")




@section('content')
    <div class="panel">

        <div class="panel panel-body">
            <h4>{{  $entity->created_at }} - {{  $entity->creator->locale_name }}</h4>
            <h5>الوصف : {{ $entity->description }}</h5>

        </div>

        <table class="table table-bordered table-sorted">
            <thead>
            <tr>
                <td>الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>الوصف</td>
            </tr>

            </thead>
            <tbody>
            @foreach($entity->transactions as $transaction)
                <tr>
                    <td>{{ $transaction->account_name }}</td>
                    @if($transaction->type == 'debit')
                        <td>{{ displayMoney($transaction->amount) }}</td>
                        <td>0</td>
                    @else
                        <td>0</td>
                        <td>{{ displayMoney($transaction->amount) }}</td>
                    @endif

                    <td>{{ $transaction->description }}</td>
                </tr>
            @endforeach

            <tr class="bg-primary">
                <th></th>
                <th>{{displayMoney($entity->total_debit_amount)}}</th>
                <th>{{displayMoney($entity->total_credit_amount)}}</th>
                <th></th>
                <th></th>
            </tr>
            </tbody>
        </table>
    </div>



@stop
