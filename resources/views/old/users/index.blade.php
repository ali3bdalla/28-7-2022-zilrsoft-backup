@extends('old.layouts.master2')

@section('title',__('sidebar.users'))
@section('desctipion','users list')
@section('route',route('management.users.index'))


@section('content')
    <div class="box">

        <div class="panel-heading">
            <a href="{{ route('management.users.create') }}"
               class="button is-primary btn-outline-primary"><i
                        class="fa fa-plus-circle"></i> {{ trans('pages/users.create') }}</a>
        </div>
        <div class="">
            @empty(!$users)
                <div class="table-responsive text-center">
                    <table class="table table-bordered text-center table-hover table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>الصلاحيات</th>
                            <th>التاريخ</th>
                            <th>المستخدم</th>
                            <th>خيارات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $user->name }}</td>

                                <td>@if(!$user->is_system_user){{ $user->phone_number }}@endif</td>
                                <td>
                                    @foreach($user->membership_type() as $membership)
                                        {!! $membership !!}
                                    @endforeach
                                </td>
                                <td>{{ $user->created_date }}</td>
                                <td>{{ $user->creator_user() }}</td>

                                <td>
                                    <a href="{{ route('management.users.edit',$user->id) }}" class="button is-primary
                                    btn-sm"><i
                                                class="fa fa-edit"></i>
                                        &nbsp;
                                        &nbsp;
                                        تعديل
                                    </a>

                                    <a href="{{ route('management.users.show',$user->id) }}" class="button is-info
                                    btn-sm"><i class="fa fa-eye"></i> &nbsp; &nbsp; عرض
                                    </a>

                                    @if(!$user->is_system_user)
                                        <form class="" action="{{ route('management.users.destroy',
                                        $user->id) }}" method="post">
                                            @method("DELETE")
                                            @csrf

                                            <div class="form-inline" style="margin-top: 5px">
                                                <button class="button is-danger btn-sm"><i class="fa fa-trash"></i>
                                                    &nbsp; &nbsp; حذف
                                                </button>
                                            </div>
                                        </form>

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
