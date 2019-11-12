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
                @foreach($items as $history)

                        <?php
                        $total_amount = $total_amount + $history->receipt_total - $history->payment_total;
                        ?>


                        <tr>
                            <th class="text-center ">{{$history->created_at}}</th>
                            <th class="text-center ">{{$history->id}}</th>
                            <th class="text-center ">-</th>
                            <th class="text-center "><a href="{{route('management.charts.item',
                             $history->id) }}">{{ $history->name }}</a> </th>
                            <th class="text-center ">{{ $history->receipt_total }}</th>
                            <th class="text-center ">{{ $history->payment_total }}</th>
                            <th class="text-center ">{{ $total_amount  }}</th>

                        </tr>




                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

