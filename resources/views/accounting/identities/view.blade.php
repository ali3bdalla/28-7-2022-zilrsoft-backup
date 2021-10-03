@extends('accounting.layout.master')

@section('title',__('pages/users.view') . " | ". $identity->locale_name)



@section("page_css")
    <style type="text/css">
        @import url(http://fonts.googleapis.com/css?family=Lato:400,700);

        body {
            font-family: 'Lato', 'sans-serif';
        }

        .profile {
            min-height: 355px;
            display: inline-block;
        }

        figcaption.ratings {
            margin-top: 20px;
        }

        figcaption.ratings a {
            color: #f1c40f;
            font-size: 11px;
        }

        figcaption.ratings a:hover {
            color: #f39c12;
            text-decoration: none;
        }

        .divider {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .emphasis {
            border-top: 4px solid transparent;
        }

        .emphasis:hover {
            border-top: 4px solid #1abc9c;
        }

        .emphasis h2 {
            margin-bottom: 0;
        }

        span.tags {
            background: #1abc9c;
            border-radius: 2px;
            color: #f5f5f5;
            font-weight: bold;
            padding: 2px 4px;
        }

        .dropdown-menu {
            background-color: #34495e;
            box-shadow: none;
            -webkit-box-shadow: none;
            width: 250px;
            margin-left: -125px;
            left: 50%;
        }

        .dropdown-menu .divider {
            background: none;
        }

        .dropdown-menu > li > a {
            color: #f5f5f5;
        }

        .dropup .dropdown-menu {
            margin-bottom: 10px;
        }

        .dropup .dropdown-menu:before {
            content: "";
            border-top: 10px solid #34495e;
            border-right: 10px solid transparent;
            border-left: 10px solid transparent;
            position: absolute;
            bottom: -10px;
            left: 50%;
            margin-left: -10px;
            z-index: 10;
        }
    </style>
@endsection




@section("content")
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6">
                <div class="well profile">
                    <div class="col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <h2>{{  $identity->locale_name }}</h2>
                            <p><strong>{{ trans('pages/users.identityType') }}</strong> :
                                {{ trans("pages/users.$identity->user_type")}} </p>
                            <p><strong>{{ trans('pages/users.title') }}</strong> :
                                {{ trans("pages/users.$identity->user_title")}} </p>
                            <p><strong>{{ trans('pages/users.email_address') }}</strong> :
                                {{ $identity->details ? $identity->details->email : "" }} </p>
                            <p><strong>{{ trans('pages/users.identitySubscriptions') }}: </strong>
                                @if($identity->is_client) <span class="label label-primary">
                                    {{ trans('pages/users.client')
                               }}</span>@endif
                                @if($identity->is_vendor)<span class="label label-success">
                                    {{ trans('pages/users.vendor')}}</span>@endif
                                @if($identity->is_supplier)<span class="label label-warning">
                                    {{ trans('pages/users.supplier')}}</span>@endif
                            </p>
                            <p><strong>{{ trans('pages/users.address') }}</strong> :
                                {{ $identity->details ? $identity->details->address : ""}} </p>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <p><strong>{{ trans('pages/users.created_at') }}</strong> :
                                {{ $identity->created_at}} </p>
                            <p><strong>{{ trans('pages/users.phone_number') }}</strong> :
                                {{ $identity->phone_number}} </p>
                            <p><strong>{{ trans('pages/users.work_manager_name') }}</strong> :
                                {{ $identity->details ? $identity->details->responsible_name : ""}} </p>
                            <p><strong>{{ trans('pages/users.work_phone_number') }}</strong> :
                                {{ $identity->details ? $identity->details->responsible_phone_number : ""}} </p>


                            @if($identity->is_vendor)
                                <p><strong>{{ trans('pages/users.vat_number') }}</strong> :
                                    {{$identity->details ?  $identity->details->vat : ""}} </p>
                                <p><strong>{{ trans('pages/users.cr_number') }}</strong> :
                                    {{$identity->details ?  $identity->details->cr : "" }} </p>
                            @endif

                        </div>
                    </div>
                    <div class="col-xs-12 divider text-center">
                        @if($identity->is_client)
                            <div class="col-xs-12 col-sm-6 emphasis">
                                <h2><strong>{{ moneyFormatter($identity->balance) }} </strong></h2>
                                <p><small>{{ trans('pages/users.client_balance') }}</small></p>

                                <a href="{{ route('entities.user',[$clientAccount->id,$identity->id])}}">كشف
                                    حساب</a>
                            </div>
                        @endif
                        @if($identity->is_vendor)
                            <div class="col-xs-12 col-sm-6 emphasis">
                                <h2><strong>{{ moneyFormatter($identity->vendor_balance) }} </strong></h2>
                                <p><small>{{ trans('pages/users.vendor_balance') }}</small></p>
                                <a href="{{ route('entities.user',[$vendorAccount->id,$identity->id])}}">كشف
                                    حساب</a>
                            </div>
                        @endif


                    </div>
                    @if(!empty($identity->gateways))
                    <div class="col-xs-12 divider">
                        <h2><strong>{{ trans('pages/users.gateways')  }} </strong></h2>
                        @foreach($identity->gateways as $gateway)
                            <p><strong>{{ $gateway->bank->locale_name }} </strong> : <small>
                                    {{ $gateway->detail }}</small></p>
                        @endforeach

                    </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
@endsection





@section("after_content")
@endsection
