<div class="panel panel-default">
    <div class="panel-heading">
        <button class="btn btn-primary" wire:click="save">{{ __("pages/invoice.save_and_print_receipt") }}</button>
    </div>
    <div class="panel-body">
        <div class="raw">
            <div class="col-md-3 col-xs-12">
                <livewire:customers-selector-component/>
                @error('customerId')
                    <div class="alert alert-danger"> {{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 col-xs-12">

            </div>
        </div>
        <div class="raw">
            <div class="col-xs-12">
                <livewire:item-picker-component/>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="raw">
            <div class="col-xs-12">
                <livewire:sale-receipt-items-component/>
                @error('items')
                <div class="alert alert-danger"> {{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    {{--    <div class="panel-body">--}}
    {{--        <div class="raw">--}}
    {{--            <div class="col-md-3">--}}
    {{--                <div class="list-group">--}}
    {{--                    <div class="list-group-item text-center">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-md-6"><label>المجموع</label></div>--}}
    {{--                            <div class="col-md-6">{{ showMoney($items->sum('total') )}}</div>--}}
    {{--                        </div>--}}
    {{--                        1--}}
    {{--                    </div>--}}
    {{--                    <div class="list-group-item text-center">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-md-6"><label>الصافي</label></div>--}}
    {{--                            <div class="col-md-6">{{showMoney($items->sum('subtotal'))}}</div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="list-group-item text-center">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-md-6"><label> الضريبة</label></div>--}}
    {{--                            <div class="col-md-6">{{showMoney($items->sum('tax'))}}</div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="list-group-item text-center">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-md-6"><label>النهائي</label></div>--}}
    {{--                            <div class="col-md-6">{{showMoney($items->sum('net'))}}</div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>
