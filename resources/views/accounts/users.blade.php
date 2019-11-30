@extends('layouts.master2')


@section('title',$account->locale_name)





@section('content')



    <div class="card">


        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "> #</th>
                    <th class="text-center ">الرقم</th>
                    <th class="text-center ">المدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">الرصيد</th>
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
                        <th class="text-center ">{{ $user['id'] }}</th>
                        <th class="text-center ">{{ $user['id'] }}</th>
                        <th class="text-center ">{{ $user['id'] }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

