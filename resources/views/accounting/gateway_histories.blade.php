@extends('layouts.master2')


@section('title',$chart->name)


{{--@section('translator')--}}
{{--    <script defer>--}}
{{--        window.translator = '@json(trans('pages/invoice'))'--}}
{{--    </script>--}}
{{--@stop--}}




@section('content')


    <div class="box">
        <div class="card-body">

        </div>
    </div>

    <div class="card">


        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">الرقم</th>
                    <th class="text-center ">الهوية</th>
                    <th class="text-center ">بيان</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">رصيد</th>
                </tr>
                </thead>

                <tbody>
			 <?php
			 $total_amount = 0;
			 ?>
                @foreach($payments as $history)

                    @if($history['is_main_child'])

                        <?php
                        $total_amount = $total_amount + $history->receipt_total - $history->payment_total;
                        ?>


                         <tr>
                             <th class="text-center ">-</th>
                             <th class="text-center ">-</th>
                             <th class="text-center ">{{$history['user']['name']}}</th>
                             <th class="text-center "><a href="{{route('management.charts.show',
                             $history->id) }}">{{ $history->gateway->name }}</a> </th>
                             <th class="text-center ">{{ $history->receipt_total }}</th>
                             <th class="text-center ">{{ $history->payment_total }}</th>
                             <th class="text-center ">{{ $total_amount  }}</th>

                         </tr>


                    @else

	                    <?php $total_amount = $history->gatewayRawBalance($total_amount)?>


                         <tr>
                             <th class="text-center ">{{$history['created_at']}}</th>
                             <th class="text-center ">{{$history['id']}}</th>
                             <th class="text-center ">{{$history['user']['name']}}</th>
                             <th class="text-center "><a href="{{route('management.charts.show',$history->gateway->chart->id) }}">{{ $history->gateway->chart->name }}</a> </th>
                             <th class="text-center ">{{ $history->gateway_debit_value }}</th>
                             <th class="text-center ">{{ $history->gateway_credit_value }}</th>
                             <th class="text-center ">{{ $total_amount  }}</th>

                         </tr>
                    @endif




                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

