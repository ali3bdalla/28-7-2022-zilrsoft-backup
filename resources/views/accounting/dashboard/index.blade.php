@extends('accounting.layout.master')

@section('title',__('sidebar.dashboard'))



@section("before_content")

@endsection




@section("content")
    {{-- @can(' view charts') --}}
        {{-- <div class="row">
            <div class="col-md-4">
                <accounting-dashboard-active-items-layout-component>

                </accounting-dashboard-active-items-layout-component>

            </div>
            <div class="col-md-4">
                <accounting-dashboard-items-chart-component
                        type="bar"
                        title="منتجات">

                </accounting-dashboard-items-chart-component>
            </div>
            <div class="col-md-4">
                <accounting-dashboard-items-chart-component
                        type="radar"
                        title="فلاتر">

                </accounting-dashboard-items-chart-component>
            </div>
        </div> --}}
        {{--    <div class="row">--}}
        {{--        <div class="col-md-4">--}}
        {{--            <accounting-dashboard-items-chart-component--}}
        {{--                    type="polarArea"--}}
        {{--                    title="عمليات">--}}

        {{--            </accounting-dashboard-items-chart-component>--}}
        {{--        </div>--}}
        {{--        <div class="col-md-4">--}}
        {{--            <accounting-dashboard-items-chart-component--}}
        {{--                    type="doughnut"--}}
        {{--                    title="منتجات">--}}

        {{--            </accounting-dashboard-items-chart-component>--}}
        {{--        </div>--}}
        {{--        <div class="col-md-4">--}}
        {{--            <accounting-dashboard-items-chart-component--}}
        {{--                    type="pie"--}}
        {{--                    title="فلاتر">--}}

        {{--            </accounting-dashboard-items-chart-component>--}}
        {{--        </div>--}}
        {{--    </div>--}}
    {{-- @endcan --}}
@endsection





@section("after_content")
@endsection