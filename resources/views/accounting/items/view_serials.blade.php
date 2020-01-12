@extends('accounting.layout.master')

@section('title',__('pages/items.view_serials') . " | " . $item->locale_name )


@section("before_content")

@endsection



@section("content")
    <div class="panel">
        @if(isset($serials))
            <table class="table table-bordered text-center table-primary">
                <thead class="panel-heading">
                <tr>
                    <th>#</th>
                    <th>السيريال</th>
{{--                    <th>الباكود</th>--}}
                    <th>الحالة</th>

                </tr>
                </thead>
                <tbody>
                @foreach($serials as $serial)
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>{{$serial->serial}}</td>
{{--                        <td>--}}
{{--                            <accounting-show-barcode-layout-component :barcode="{{$serial->serial}}">--}}
{{--                            </accounting-show-barcode-layout-component>--}}
{{--                        </td>--}}
                        <td>{{trans('pages/items.' . $serial->current_status)}} </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $serials->links()}}
        @endif
    </div>

@endsection





@section("after_content")
@endsection