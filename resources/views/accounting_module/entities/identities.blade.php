@extends('accounting.layout.master')


@section('title',$account->locale_name)





@section('content')

    <div class="panel">
        {{-- <div class="panel-heading">
        </div> --}}
        <div class="panel-body">
            <table class="table table-bordered text-center table-striped">
                <thead>
                <tr>
                    <th class="text-right ">اسم الهوية</th>
                    <th class="text-center ">الرصيد</th>
                </tr>
                </thead>

                <tbody>

                @foreach($identities as $identity)
                    <tr>
                        <th class=" " style="text-align:right !important; padding-right:5px !important;"><a
                                    href="{{ route('accounts.show.identity',[ $account->id,$identity['id']] ) }}">{{$identity->locale_name}}</a>
                        </th>
                        {{-- @if($account->slug == 'vendors') --}}
                            <th class="text-center">{{displayMoney($identity->getYearlyBalance($account)) }}</th>
                            {{-- <th class="text-center">{{displayMoney($identity->vendor_balance) }}</th> --}}
                        {{-- @else
                            <th class="text-center ">{{displayMoney($identity->balance) }}</th>
                        @endif --}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

