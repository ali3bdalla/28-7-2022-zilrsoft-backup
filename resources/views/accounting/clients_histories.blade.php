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
                @foreach($clients as $client)



                    <tr>
                        <th class="text-center ">{{$client->created_at}}</th>
                        <th class="text-center ">{{$client->id}}</th>

                        <th class="text-center "><a href="{{route('accounts',[
                             $client->id,$chart->id]) }}">{{ $client->name }}</a></th>
                        <th class="text-center ">-</th>
                        <th class="text-center ">{{ money_format("%i",$client->histories['total_debit'])}}</th>
                        <th class="text-center ">{{money_format("%i",$client->histories['total_credit'])  }}</th>
                        <th class="text-center ">{{ money_format("%i",$client->histories['total_debit'] -
                         $client->histories['total_credit'])  }}</th>

                    </tr>




                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

