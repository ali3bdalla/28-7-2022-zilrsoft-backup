@if(!empty($invoice->payments))
    <div class="panel panel-primary">
        <div class="panel-heading  bg-custom-primary"><label>{{ trans('pages/invoice.payments_methods') }}</label></lable></div>
        <div class="panel-body">
            @foreach($invoice->payments as $payment)
                <div class="form-group">
                    <div class="input-group"><span id="1" class="input-group-addon"
                                                   style="min-width: 130px; font-weight: bolder;
">
                    {{$payment->account->locale_name}} &nbsp;
                    &nbsp; ( <a target="_blank" href="{{ route('vouchers.show',$payment->id)}}">عرض
                        السند</a> )</span>
                        <input aria-describedby="1" disabled="disabled" type="text"
                               class="form-control" value="{{displayMoney($payment->amount)}}" style="font-weight:
                                                   bolder;">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif