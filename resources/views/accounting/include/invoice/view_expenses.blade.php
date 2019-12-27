<div class="panel panel-primary">
    <div class="panel-heading">
        تكاليف اضافية
    </div>

    @if(in_array($invoice->invoice_type,['sale','r_sale']))
        @foreach($invoice->items as $item)
            @if($item->item->is_expense)
                <tr>

                    <!-- <th class="has-text-white"></th> -->

                    <th text="item.name"
                        style="text-align: right !important;">{{ $item->item->locale_name }}</th>

                    <th class="has-text-white">
                        <input type="text" class="input" value="{{ $item->price }}"
                               disabled="">

                    </th>

                    <th class="has-text-white">
                        <input type="text" class="input" placeholder="tax" readonly=""
                               value="{{ $item->tax
                                }}" disabled="">
                    </th>
                    <th class="has-text-white">
                        <input type="text" class="input" placeholder="net" readonly=""
                               value="{{ $item->net
                                }}" disabled="">
                    </th>

                </tr>
            @endif
        @endforeach

    @else


        <div class="panel-body">

            <table class="table table-bordered">
                @foreach($invoice->expenses as $expense)
                    <tr>
                        <th text="item.name"
                            style="text-align: right !important;">{{
                                                    $expense->expense->locale_name }} - @if($expense->with_net)
                                <span class="badge badge-primary">مضمنة</span>


                            @else
                                <span class="badge badge-info">مستقلة</span>
                            @endif
                        </th>

                        <th class="has-text-white">
                            <input type="text" class="form-control input-xs amount-input" value="{{ $expense->amount }}"
                                   disabled="">

                        </th>

                    </tr>
                @endforeach
            </table>
        </div>
    @endif

</div>
