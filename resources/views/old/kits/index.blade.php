@extends('old.layouts.master2')


@section('title',__('sidebar.kits'))
@section('desctipion','items')
@section('route',route('management.items.index'))

@section('content')
    <div class="box">
        <div class="card-body">
            <a href="{{route('management.kits.create')}}" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; {{ __('pages/items.create_kit') }}</a>
            <span class="subtitle"></span>

            <br>
        </div>
    </div>

    <div class="card">
        <div class="">


            @if(isset($items))
                <table class="table is-borderd table-bordered text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pages/items.name') }}</th>
                        <th>{{ __('pages/items.barcode') }}</th>
                        <th>{{ __('pages/items.items_count') }}</th>
                        <th>{{ __('reusable.date') }}</th>
                        <th>{{ __('reusable.creator') }}</th>
                        <th>{{ __('reusable.manage') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>
                                <p>{{$item->name}}</p>
{{--                                <p>{{$item->ar_name}}</p>--}}
                            </td>
                            <td>{{$item->barcode}}</td>
                            <td>{{$item->items()->count()}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->creator->name}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="button is-primary" type="button" id="optionsMenu{{ $item->id}}"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; {{ __('reusable.manage') }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $item->id}}">
                                        <a class="dropdown-item" href="{{ route("management.kits.edit",$item->id)
                                        }}"><i class="fa fa-eye"></i> &nbsp;{{ __('reusable.view') }}</a>

                                        <form method="post" action="{{ route
                                        ('management.kits.destroy',$item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item is-danger" type="submit"><i
                                                        class="fa fa-trash"></i> &nbsp; {{ __('reusable.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $items->links()}}
            @endif
        </div>
    </div>
@stop
