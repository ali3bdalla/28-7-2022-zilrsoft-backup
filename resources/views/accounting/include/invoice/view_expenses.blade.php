@if(in_array($invoice->invoice_type,['sale','r_sale']))

@else

    <div class="panel panel-primary">
        <div class="panel-heading">
            تكاليف اضافية
        </div>
        <div class="panel-body">

            <table class="table table-bordered">
                {{$invoice->expenses}}
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
    </div>

@endif