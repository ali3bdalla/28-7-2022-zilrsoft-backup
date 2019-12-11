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
			 $total_balance = 0;
			 ?>
                @foreach($activities as $history)



                    <?php $total_balance = ($total_balance + $history->debit - $history->credit); ?>

                    <tr>
                        <th class="text-center datedirection">{{ $history->created_at }}</th>
                        <th class="text-center ">{{ $history->invoice->id }}</th>
                        <th class="text-center ">-</th>
                        <th class="text-center "><a href="{{route('management.sales.show',$history->invoice_id) }}">{{
                        $history->invoice->title }}</a></th>
                        <th class="text-center ">{{ money_format("%i",$history->debit) }}</th>
                        <th class="text-center ">{{ money_format("%i",$history->credit) }}</th>
                        <th class="text-center ">{{ money_format("%i",$total_balance)}}</th>

                    </tr>



                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop
