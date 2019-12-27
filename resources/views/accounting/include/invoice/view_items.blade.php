<div class="table-responsive">
    <table class="table table-bordered text-center  table-striped">
        <thead class="">
        <tr>
            <th class="">{{ __('pages/invoice.id') }}</th>
            <th class="">{{ __('pages/invoice.barcode') }}</th>
            <th class="" width="20%">{{ __('pages/invoice.item_name') }}</th>
            <th class="">{{ __('pages/invoice.qty') }}</th>
            <th class="">{{ __('pages/invoice.price') }}</th>
            <th class="">{{ __('pages/invoice.total') }}</th>
            <th class="">{{ __('pages/invoice.discount') }}</th>
            <th class="">{{ __('pages/invoice.subtotal') }}</th>
            <th class="">{{ __('pages/invoice.vat') }}</th>
            <th class="">{{ __('pages/invoice.tax') }}</th>
            <th class="">{{ __('pages/invoice.net') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($items as $item)
            @if(!(in_array($invoice->invoice_type,['sale','r_sale']) && $item->is_expense))
                <tr @if($item->belong_to_kit==true) class="bg-custom-primary" @endif>
                    <td>
                        <button class="btn btn-custom-primary btn-xs">{{$loop->index + 1}}</button>
                    </td>
                    <td>{{ $item->item->barcode }}</td>
                    <td>{{ $item->item->locale_name }}</td>
                    <td>
                        <input type="text" class="form-control input-sm amount-input" value="{{ $item->qty }}"
                               disabled="">
                    </td>
                    <td>
                        <input type="text" class="form-control input-sm amount-input" value="{{ $item->price }}"
                               disabled="">

                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" value="{{ $item->total }}"
                               disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="discount"
                               value="{{ $item->discount }}"
                               disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="subtotal" readonly=""
                               value="{{
                                $item->subtotal }}" disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="vat purchase"
                               readonly=""
                               value="{{
                                $item->item->vtp }}%" disabled="">
                    </td>
                    <td class="text-center">
                        <input type="text" class="form-control input-sm amount-input" placeholder="tax" readonly=""
                               value="{{ $item->tax
                                }}" disabled="">
                    </td>
                    <td class="">
                        <input type="text" class="form-control input-sm amount-input" placeholder="net" readonly=""
                               value="{{ $item->net
                                }}" disabled="">
                    </td>

                </tr>
            @endif
        @endforeach


        </tbody>
    </table>
</div>

