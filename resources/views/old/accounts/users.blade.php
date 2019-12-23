@extends('old.layouts.master2')


@section('title',$account->locale_name)





@section('content')



    <div class="card">


        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الرصيد</th>
                </tr>
                <tr>
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">رقم</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>

                @foreach($users as $user)


                    <tr>
                        <th class="text-center ">{{ $user['id'] }}</th>
                        @if($account->slug=='clients')
                            <th class="text-center "><a
                                        href="{{ route('management.accounts.client',[ $user['id'],$account->id] ) }}">{{
                        $user['name'] }}</a></th>

                        @else

                            <th class="text-center "><a
                                        href="{{ route('management.accounts.vendor',[ $user['id'],$account->id] ) }}">{{
                        $user['name'] }}</a></th>
                        @endif
                        <th class="text-center ">{{ money_format("%i",$user['total_debit']) }}</th>
                        <th class="text-center ">{{ money_format("%i",$user['total_credit']) }}</th>
                        <th class="text-center ">{{ money_format("%i",$user['balance_debit']) }}</th>
                        <th class="text-center ">{{ money_format("%i",$user['balance_credit']) }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

