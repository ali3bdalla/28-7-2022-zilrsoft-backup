@extends("layouts.master2")

@section('title',__('sidebar.expenses'))
@section('desctipion','our organization expenses list')
@section('route',route('management.expenses.index'))

@section("content")
    <div class="box">
        <div class="card-body">
            <a href="{{ route("management.expenses.create") }}" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; {{ __('pages/expenses.create') }}</a>
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


        @if(isset($expenses) and !empty($expenses))

            <table class="table table-dark table-striped table-bordered text-center table-hover table-" width='100%'>
                <thead class="thead-dark">
                <tr>
                    <td>{{__('pages/expenses.id')}}</td>
                    <td>{{__('pages/expenses.name')}}</td>
                    <td>{{__('pages/expenses.ar_name')}}</td>
                    <td >{{__('reusable.date')}}</td>
                    <td>{{__('reusable.creator')}}</td>
                    <td>{{__('reusable.manage')}}</td>
                </tr>
                </thead>

                <tbody>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense['id'] }}</td>
                        <td>{{ $expense['name'] }}</td>
                        <td>{{ $expense['ar_name'] }}</td>
                        <td class="datedirection">{{ $expense['created_at'] }}</td>
                        <td>
                            <a href="{{ route("management.users.show",$expense->creator_id) }}">{{ $expense->creator->name }}</a>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="button is-primary" type="button" id="optionsMenu{{ $expense->id}}"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> &nbsp; {{__('reusable.manage')}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $expense->id}}">
{{--                                    <a class="dropdown-item" href="{{ route("management.expenses.edit",--}}
{{--                                            $expense['id']) }}"><i class="fa fa-edit"></i> &nbsp; {{__('reusable.update')--}}
{{--                                            }}</a>--}}

                                    <form method="post" style="display: inline;" class="" action="{{route('management.expenses.destroy',$expense->id)
                                    }}" >
                                        @csrf()
                                        @method('delete')
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
    </div>
    @endif


@endsection
