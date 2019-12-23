@extends('layouts.master2')


@section('title',__('sidebar.kits'))
@section('desctipion','items')
@section('route',route('management.items.index'))

@section('content')


    <div class="card">
        <div class="">


            @if(isset($serials))
                <table class="table is-borderd table-bordered text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>السيريال</th>
                        <th>الحالة</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($serials as $serial)
                        <tr>
                            <td>{{$serial->id}}</td>
                            <td>{{$serial->serial}}</td>
                            <td>{{$serial->current_status}} </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $serials->links()}}
            @endif
        </div>
    </div>
@stop
