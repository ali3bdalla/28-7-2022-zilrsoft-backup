@extends('old.layouts.master2')

@section('title','الحسابات المالية | ' . $user->name)
@section('desctipion',__('pages/settings.payments'))
@section('route',route('management.settings.index'))


@section('content')
    <div class="box">

        <div class="card-body">
            <a href="{{route('management.users.create_payments_accounts',$user->id)}}" class="button is-info
            pull-right">
                <i class='fa  fa-plus-circle'></i>&nbsp;اضافة حساب مالي جديد
            </a>
{{--            <span class="subtitle">{{ __('pages/settings.payments') }}</span>--}}
            <br>
        </div>
    </div>

    <div class="">

        <div class="panel">
            @foreach($accounts as $key => $account)

                <div class="panel-body">
                    <h1 class="title">
                        {{ $account[0]['gateway']['name'] }}
                    </h1>
                </div>
                <div class="panel-footer">
                    <div class="tags are-large">
                        @foreach($account as $acc)
                            <span class="tag is-primary ">
                             @if(!empty($acc['bank']))
                                    : {{ $acc['bank']['name'] }}
                                @endif

                                {{ $acc['account'] }} &nbsp;&nbsp;
                            @if(!empty($acc['account_name']))

                                    : {{ $acc['account_name'] }} &nbsp;&nbsp;
                                @endif
                            <a href="" class="delete is-small"></a>

                        </span> &nbsp;&nbsp;&nbsp;&nbsp;
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection