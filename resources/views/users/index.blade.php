@extends('layouts.master2')

@section('title',__('sidebar.users'))
@section('desctipion','users list')
@section('route',route('management.users.index'))


@section('content')
    <div class="box">

        <div class="ibox-head">
            <div class="ibox-title">{{ trans('users.table_list',['users'=>'All Users']) }}</div>
            <a href="{{ route('management.users.create') }}"
               class="float-right button is-primary btn-outline-primary  create-new-button"><i
                        class="fa fa-plus-circle"></i> {{ trans('users.create_new_user') }}</a>
        </div>
        <div class="ibox-body">
            @empty(!$users)
                <div class="table-responsive text-center">
                    <table class="table table-bordered text-center table-hover table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>username</th>
                            <th>Phone Number</th>
                            <th>membership type</th>
                            <th>Created At</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>
                                    @foreach($user->membership_type() as $membership)
                                        {!! $membership !!}
                                    @endforeach
                                </td>
                                <td>{{ $user->created_date }}</td>
                                <td>{{ $user->creator_user() }}</td>

                                <td>
                                    @if(!$user->is_system_user)

                                        {{--                                  <a href='{{route('management.users.show',$user->id)}}' class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip" data-original-title="view"><i class="fa fa-user font-14"></i></a>--}}


                                        <a href='{{route('management.users.update_payments_accounts',$user->id)}}'
                                           class="btn
                                    btn-primary btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i
                                                    class="fa fa-info-circle font-14"></i></a>


                                    <!-- <a href='{{route('management.users.show',$user->id)}}' class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip" data-original-title="view"><i class="fa fa-credit-card font-14"></i></a> -->



                                    <!-- @if($user->is_manager && !$user->is_supervisor )
                                        <a class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></a>
                                    @endif -->

                                    <!-- <form class='form-inline delete-table-form' method="post" action="{{ route('management.users.destroy',$user->id) }}">
                                    @method('DELETE')
                                        @csrf
                                            <button class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash font-14"></i></button>
                                            </form> -->
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>


                    </table>
                    {{ $users->onEachSide(1)->appends(Request::except('page'))->links() }}
                </div>
            @else


            @endempty


        </div>
    </div>
@endsection
