@extends('accounting.layout.master')


@section('title',$account->locale_name)





@section('content')


    <div class="panel">

        <div class="panel-heading">

        </div>

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
                    <th class="text-center "> #</th>
                    <th class="text-center ">الرقم</th>
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
                        @if($account->slug=='clients')
                            <th class="text-center "><a
                                        href="{{ route('accounting.accounts.client',[ $item['id'],$account->id] ) }}">{{
                        $item['locale_name'] }}</a></th>

                        @else

                            <th class="text-center "><a
                                        href="{{ route('accounting.accounts.item',[ $item['id'],$account->id] ) }}">{{
                        $item['locale_name'] }}</a></th>
                        @endif
                        <th class="text-center ">{{money_format("%i", $item['total_debit']) }}</th>
                        <th class="text-center ">{{money_format("%i", $item['total_credit'])}}</th>
                        <th class="text-center ">{{ money_format("%i", $item['balance_debit']) }}</th>
                        <th class="text-center ">{{money_format("%i", $item['balance_credit']) }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

