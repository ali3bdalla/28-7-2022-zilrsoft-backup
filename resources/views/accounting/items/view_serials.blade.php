@extends('accounting.layout.master')

@section('title',__('pages/items.view_serials') . " | " . $item->locale_name )


@section("before_content")

@endsection



@section("content")
    <div class="panel">
        <div class="panel-heading">
            <form action="#" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <select class="form-control" name="status">
                            <option>حالة السيريال</option>
                            <option value="in_stock" @if($status == "in_stock") selected @endif>متوفر</option>
                            <option value="return_sale" @if($status == "return_sale") selected @endif>مرتجع مبيعات</option>
                            <option value="return_purchase" @if($status == "return_purchase") selected @endif>مرتجع مشتريات</option>
                            <option value="sold" @if($status == "sold") selected @endif>مباع</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary text-white btn-block">فلترة</button>
                    </div>
                </div>
            </form>
        </div>
        @if(isset($serials))
            <table class="table table-bordered text-center table-primary">
                <thead class="panel-heading">
                <tr>
                    <th>#</th>
                    <th>السيريال</th>
                    <th>الحالة</th>

                </tr>
                </thead>
                <tbody>
                @foreach($serials as $serial)
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>{{$serial->serial}}</td>

                        <td>{{trans('pages/items.' . $serial->status)}} </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $serials->appends($_GET)->links()}}
        @endif
    </div>

@endsection





@section("after_content")
@endsection