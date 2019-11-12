@extends('layouts.master2')
@section('title',$pitem->name)
@section('desctipion',$pitem->barcode)
@section('route',route('management.items.index'))



@section("title")
    {{ $pitem->name }}
@endsection



@section("page_js")
@endsection








@section("content")

    <div class="box">
        <table id="" class="text-center table is-bordered is-dark"style="width:100%;">
            <thead class="thead-dark">

            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="3">in</th>
                <th colspan="3" class="has-background-light">out</th>
                <th colspan="3" >balance</th>
                <th></th>



            </tr>



            <tr>
                <th>date</th>
                <th>invoice</th>
                <th>id</th>
                <th>employee</th>
                <th>qty</th>
                <th>price</th>
                <th>value</th>
                <th class="has-background-light">qty</th>
                <th class="has-background-light">price</th>
                <th class="has-background-light">value</th>
                <th>avl qty</th>
                <th>cost</th>
                <th>stock value</th>
                <th>description</th>


            </tr>
            </thead>
            <tbody>
            @if(!empty($items))
                <?php
                $history_qty = 0;
                $history_total_balance  = 0;
                $history_avg_cost = 0;
                $last_cost = 0;
                ?>
                @foreach($items as $item)

                    @if($item['invoice_type']=='purchase' || $item['invoice_type']=='beginning_inventory')
                        <?php
                        $history_qty = $history_qty + $item['qty'];
                        $history_total_balance = $history_total_balance + $item['total'];
                        if($history_qty>0){
                            $history_avg_cost = $history_total_balance / $history_qty;
                        }

                        if($item->is_service){
                            $history_qty  = 0;
                        }
                        ?>
                        <tr  @if($item['invoice_type']=='purchase')class="" @else class="" @endif>
                            <td class="datedirection">{{ $item['created_at'] }}</td>
                            <td>{{ $item->invoice->purchase->id }} </td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->creator->name }}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{ $item['total'] }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $history_qty }}</td>
                            <td>{{ $history_avg_cost   }}</td>
                            <td>{{ $history_total_balance }}</td>
                            <td>@if($item['invoice_type']=='purchase')purchase @else beginning inventory @endif</td>
                        </tr>
                        @if($item['discount']>0)
                            <?php
                            $history_total_balance = $history_total_balance - $item['discount'];
                            if($history_qty>0){
                                $history_avg_cost = $history_total_balance / $history_qty;
                            }
                            ?>
                            <tr class="">
                                <td></td>
                                <td>{{ $item->invoice->invoice_full_title }} </td>
                                <td>{{$item->user->name}}</td>
                                <td>{{$item->creator->name}}</td>
                                <td></td>
                                <td></td>

                                <td>{{ $item['discount'] }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $history_avg_cost  }}</td>
                                <td>{{ $history_total_balance }}</td>
                                <td>discount</td>
                            </tr>
                        @endif

                    @elseif($item['invoice_type']=='r_sale')
                        <?php

                        $history_qty = $history_qty + $item['qty'];
//                        $history_avg_cost =
                        $history_total_balance = $history_avg_cost * $history_qty;
                      //  $history_avg_cost = $history_total_balance / $history_qty;
                       // $v_history_avg_cost = $history_avg_cost;
                      //  $v_total = $history_avg_cost * $item['qty'];
                       // $history_total_balance = $history_total_balance + $v_total;
                        ?>
                        <tr class="">
                            <td>{{ $item['created_at'] }}</td>
                            <td>{{ $item->invoice->id }} </td>
                            <td>{{$item->user->full_name}}</td>
                            <td>{{$item->creator->name}}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{  $item['total'] }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$history_qty }}</td>
                            <td>{{ $history_avg_cost }}</td>
                            <td>{{ $history_total_balance }}</td>
                            <td>R sale</td>
                        </tr>
                        <?php
                        if($history_qty>0){
                            $history_avg_cost = $history_total_balance / $history_qty;
                        }

                        ?>




                    @elseif($item['invoice_type']=='r_purchase')

                        <?php

                        $history_qty = $history_qty - $item['qty'];
                        $history_total_balance = $history_total_balance -  $item->invoice->total;
                        if($history_qty>0){
                            $history_avg_cost = $history_total_balance / $history_qty;
                        }


                        $v_history_avg_cost = $history_avg_cost;
                        $v_total = $history_avg_cost * $item['qty'];
                        //  $history_total_balance = $history_total_balance + $v_total;

                        ?>
                        <tr  class="">
                            <td>{{ $item['created_at'] }}</td>
                            <td>{{ $item->invoice->id }} </td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->creator->name}}</td>
                            <td></td>
                            <td></td>
                            <td></td>


                            <td>{{ $item['qty'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{  $item['price'] * $item['qty']   }}</td>

                            <td>{{$history_qty}}</td>
                            <td>{{ number_format($history_avg_cost,2)  }}</td>
                            <td>{{ $history_total_balance }}</td>
                            <td>R purchase</td>
                        </tr>
                        <?php
                        //                            $history_avg_cost = $history_avg_cost;
                        ?>

                        @if($item['discount']>0)


                            <?php
                            $history_total_balance = $history_total_balance + $item['discount'];

                            if($history_qty>0){
                                $history_avg_cost = $history_total_balance / $history_qty;
                            }

                            ?>
                            <tr class="">
                                <td></td>
                                <td>
                                </td>
                                <td>{{$item->user->name}}</td>
                                <td>{{$item->creator->name}}</td>
                                <td></td>
                                <td></td>


                                <td></td>
                                <td></td>

                                <td></td>
                                <td>{{ $item['discount'] }}</td>
                                <td>{{ $history_qty }}</td>
                                <td>{{ $history_avg_cost  }}</td>
                                <td>{{ $history_total_balance }}</td>
                                <td>discount</td>


                            </tr>
                        @endif


                    @elseif($item['invoice_type']=='sale')

                        <?php

                        $history_qty = $history_qty - $item['qty'];
                        $vv  =$item['qty'] * $history_avg_cost;
                        $history_total_balance = $history_total_balance - $vv;

                        ?>
                        <tr class="">
                            <td>{{ $item['created_at'] }}</td>
                            <td>{{ $item->invoice->sale->id }} </td>
                            <td>{{$item->user->full_name}}</td>
                            <td>{{$item->creator->name}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $item['qty'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{ $item['total'] }}</td>
                            <td>{{$history_qty}}</td>
                            <td>{{ $history_qty!=0 ? $history_total_balance / $history_qty : 0  }}</td>
                            <td>{{ $history_total_balance }}</td>
                            <td>sale</td>
                        </tr>
                        <?php
                        if($history_qty>0){
                            $history_avg_cost =$history_total_balance / $history_qty;
                        }

                        ?>
                    @endif(in_array($item['invoice_type'],["stock_adjust"]))

                    <?php
                    $actual_qty = $item['actual_qty'];
                    $book_qty = $item['book_qty'];
                    ?>
                    @if($book_qty==$history_qty)



                        @if($item['actual_qty']<$item['book_qty'])
                            <?php
                            $avration = $item['book_qty'] - $item['actual_qty'];
                            $cost = $history_avg_cost  * $avration;
                            $history_qty = $history_qty - $avration;
                            $history_total_balance = $history_qty * $history_avg_cost;
                            ?>
                            <tr>
                                <td>{{ $item['created_at'] }}</td>
                                <td><a href="("purchases.show",$item['invoice_id']) }}">{{ $item->invoice->id }}</a>
                                </td>
                                <td><a href='("identities.show",$item->user->id) }}'>{{$item->user->name}}</a></td>
                                <td><a href='("identities.show",$item->creator->user_id) }}'>{{$item->creator->name}}</a></td>






                                <td></td>
                                <td></td>
                                <td></td>


                                <td>{{ $avration }}</td>
                                <td>{{ $history_avg_cost }}</td>
                                <td>{{ $cost }}</td>



                                <td>{{ $history_qty }}</td>
                                <td>{{ $history_avg_cost  }}</td>
                                <td>{{ $history_total_balance }}</td>


                                <td>stock</td>


                            </tr>







                        @elseif($item['actual_qty']>$item['book_qty'])
                            <?php
                            $avration = $item['actual_qty'] - $item['book_qty'] ;
                            $cost = $history_avg_cost  * $avration;
                            $history_qty = $history_qty + $avration;
                            $history_total_balance = $history_qty * $history_avg_cost;
                            ?>
                            <tr>
                                <td>{{ $item['created_at'] }}</td>
                                <td><a href="">{{ $item->invoice->invoice_full_title }}</a> </td>
                                <td><a href=''>{{$item->user->full_name}}</a></td>
                                <td><a href=''>{{$item->creator->name}}</a></td>
                                <td>{{ $avration }}</td>
                                <td>{{ $history_avg_cost }}</td>
                                <td>{{ $cost }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $history_qty }}</td>
                                <td>{{ $history_avg_cost  }}</td>
                                <td>{{ $history_total_balance }}</td>


                                <td>stock</td>


                            </tr>

                        @endif


                    @endif
                @endforeach




                <?php
                //var_dump($item['item']['id']);

                // $itemu = $item->item;
//                $item->item->cost = $history_avg_cost;
//                $item->save();

                ?>
            </tbody>

        </table>
{{--        {{ $items->links()}}--}}
        @endif


    </div>




@endsection



