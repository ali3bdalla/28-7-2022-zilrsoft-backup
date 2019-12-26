@extends("accounting.layout.master")

@section('title',__('sidebar.expenses'))
@section('buttons')
    <a href="{{ route("accounting.expenses.create") }}" class="btn btn-custom-primary"><i class='fa
                fa-plus-circle'></i>&nbsp; {{ __('pages/expenses.create') }}</a>
@endsection


@section("content")

    <div class="panel">


        @if(isset($expenses) and !empty($expenses))

            <table class="table table-dark table-striped table-bordered text-center table-hover table-" width='100%'>
                <thead class="">
                <tr>
                    <td>{{__('pages/expenses.id')}}</td>
                    <td>{{__('pages/expenses.name')}}</td>
                    <td>{{__('pages/expenses.ar_name')}}</td>
                    <td>{{__('reusable.date')}}</td>
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
                        <td class="data_field_center">{{ $expense['created_at'] }}</td>
                        <td>
                            <a href="{{ route("accounting.managers.show",$expense->creator_id) }}">{{
                            $expense->creator->name }}</a>
                        </td>
                        <td>

                            <form method="post" style="display: inline;" class=""
                                  action="{{route('accounting.expenses.destroy',$expense->id) }}">
                                @csrf()
                                @method('delete')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i> &nbsp;
                                    {{__('reusable.delete')}}</button>
                            </form>


                        </td>
                    </tr>


                @endforeach


                </tbody>
            </table>
    </div>
    @endif


@endsection

