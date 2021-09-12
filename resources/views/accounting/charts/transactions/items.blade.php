@extends('accounting.layout.master')


@section('title',$account->locale_name)





@section('content')

    <div class="panel">
        {{-- <div class="panel-heading">
        </div> --}}
        <div class="panel-body">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الارصدة</th>
                </tr>
                <tr>
                    <th class="text-center "> الباركود</th>
                    <th class="text-center ">الاسم</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>

                @foreach($items as $item)
                    <tr>
                        <th class="text-center ">{{ $item['barcode'] }}</th>
                        <th class="text-center "><a
                                    href="{{ route('accounts.show.item',[ $account->id,$item['id']] ) }}">{{$item['locale_name'] }}</a>
                        </th>
                        <th class="text-center ">{{moneyFormatter($item['total_debit_amount']) }}</th>
                        <th class="text-center ">{{moneyFormatter($item['total_credit_amount'])}}</th>
                        <th class="text-center ">{{ moneyFormatter($item['balance_debit']) }}</th>
                        <th class="text-center ">{{moneyFormatter($item['balance_credit']) }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $items->links() }}

    </div>

@stop

