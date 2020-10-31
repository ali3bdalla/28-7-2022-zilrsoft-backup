@extends('images_uploads.layout')

@section('title')
    المنتجات
@endsection
@section("content")
    <div class="text-center">
        <table class="table table-light text-center table-bordered">
            <thead>
            <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">الباركود</th>
                <th scope="col" class="text-center">اسم المنتج</th>
                <th scope="col" class="text-center">عدد الصور</th>
                <th scope="col" class="text-center">#</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <th>{{ $item->id }}</th>
                    <td>
                        <a href="https://www.google.com/search?q={{$item->barcode}}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m"
                           target="_blank">{{ $item->barcode }}</a></td>
                    <td>
                        <a href="https://www.google.com/search?q={{urlencode($item->name)}}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m"
                           target="_blank">{{$item->name }}</a>
                        <br>
                        <a href="https://www.google.com/search?q={{urlencode($item->ar_name)}}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m"
                           target="_blank">{{$item->ar_name }}</a>

                    </td>
                    <td>{{$item->attachments->count()}}</td>
                    <td><a href="/images_upload/{{$item->id}}" target="_blank" class="btn btn-primary"> المرفقات</a>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

        {{ $items->links() }}
    </div>

@endsection