@extends('accounting.layout.master')


@section('title',"قيد  - {$entity->id}")




@section('content')
    <div class="panel">

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
                <td>{{ $transaction->account->ar_name }}</td>
                @if($transaction->type == 'debit')
                    <td>{{ $transaction->amount }}</td>
                    <td>0</td>
                @else
                    <td>0</td>
                    <td>{{ $transaction->amount }}</td>
                @endif
                
                <td>{{ $transaction->description }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>


    <div class="panel panel-body">
        <h3>{{ $entity->description }}</h3>
        <h4>{{  $entity->created_at }}</h4>
    </div>
@stop
