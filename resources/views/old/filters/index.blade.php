@extends("layouts.master2")

@section('title',__('sidebar.filters'))
@section('desctipion','our organization filters list')
@section('route',route('management.filters.index'))

@section("content")
    <div class="box">
        <div class="card-body">
            <a href="{{ route("management.filters.create") }}" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; {{ __('pages/filters.create') }}</a>
            <span class="subtitle"> </span>

            <br>
        </div>
    </div>

    <div class="card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if(isset($filters) and !empty($filters))

            <table class="table table-dark table-striped table-bordered text-center table-hover table-" width='100%'>
                <thead class="thead-dark">
                <tr>
                    <td>{{__('pages/filters.id')}}</td>
                    <td>{{__('pages/filters.name')}}</td>
                    <td>{{__('pages/filters.ar_name')}}</td>
                    <td >{{__('reusable.date')}}</td>
                    <td>{{__('reusable.creator')}}</td>
                    <td>{{__('reusable.manage')}}</td>
                </tr>
                </thead>

                <tbody>
                @foreach($filters as $filter)
                    <tr>
                        <td>{{ $filter['id'] }}</td>
                        <td>{{ $filter['name'] }}</td>
                        <td>{{ $filter['ar_name'] }}</td>
                        <td class="datedirection">{{ $filter['created_at'] }}</td>
                        <td>
                            <a href="{{ route("management.users.show",$filter->creator_id) }}">{{ $filter->creator->name }}</a>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="button is-primary" type="button" id="optionsMenu{{ $filter->id}}"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> &nbsp; {{__('reusable.manage')}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $filter->id}}">
                                    <a class="dropdown-item" href="{{ route("management.filters.edit",
                                            $filter['id']) }}"><i class="fa fa-edit"></i> &nbsp; {{__('reusable.update')
                                            }}</a>

                                    <form method="post" style="display: inline;" class="">
                                        <button class="dropdown-item is-danger" type="submit"><i class="fa
                                            fa-trash"></i> &nbsp; {{__('reusable.delete')}}</button>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>


                @endforeach


                </tbody>
            </table>
            {{ $filters->links() }}
    </div>
    @endif


@endsection
