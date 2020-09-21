<div class="table-responsive">
    <table class="table table-bordered text-center  table-striped">
        <thead class="">
        <tr>
            <th class="">{{ __('pages/invoice.id') }}</th>
            <th class=""></th>
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
            @if(!(in_array($invoice->invoice_type,['sale','return_sale']) && $item->is_expense) && $item->item != null)
                <tr @if($item->belong_to_kit==true) class="bg-custom-primary" @endif>
                    <td>
                        <button class="btn btn-custom-primary btn-xs">{{$loop->index + 1}}</button>

                    </td>
                    <td>
                        @if($item->item->is_need_serial)
                            <button class="btn btn-custom-primary btn-xs" type="button" data-toggle="modal"
                                    data-target="#serialModal{{ $item->id }}"><i class="fa fa-bars"></i></button>
                        @endif
                    </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <span v-on="on"> {{ $item->item->barcode }}</span>

                            </template>
                            <span>{{ $item->cost }}</span>
                        </v-tooltip>

                       </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <span v-on="on"> {{ $item->item->locale_name }}</span>

                            </template>
                            <span>{{ $item->item->warranty_title }}</span>
                        </v-tooltip>
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


                @if($item->item->is_need_serial)
                    <div class="modal fade" id="serialModal{{ $item->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="serialModal{{ $item->id }}"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="serialModal{{ $item->id }}">السيريالات
                                        {{$item->item->locale_name}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-8 text-center">
                                            <label>السيريال</label>
                                        </div>
                                        <div class="col-md-4  text-center">
                                            <label>الحالة</label>
                                        </div>
                                    </div>
                                    @foreach($item->item->serials()->withoutGlobalScope("draftScope")
                                    ->where([
                                    ["sale_id",$invoice->id],
                                    ["item_id",$item->item->id],
                                    ])
                                    ->orWhere([["return_sale_id",$invoice->id],["item_id",$item->item->id]])
                                    ->orWhere([["return_purchase_id",$invoice->id],["item_id",$item->item->id]])
                                    ->orWhere([["purchase_id",$invoice->id],["item_id",$item->item->id]])
                                    ->get() as $serial
                                    )
                                        <div class="row">
                                            <div class="col-md-8  text-center">
                                                {{ $serial->serial }}
                                            </div>
                                            <div class="col-md-4  text-center">
                                                {{-- {{}} --}}
                                                {{trans('pages/items.' . $serial->status)}}
                                            </div>
                                        </div>



                                    @endforeach

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endif
        @endforeach


        </tbody>
    </table>
</div>

