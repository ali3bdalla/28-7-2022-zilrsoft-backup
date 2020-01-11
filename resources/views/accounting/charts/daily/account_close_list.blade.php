@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')

    <a class="btn btn-custom-primary" href="{{ route('accounting.accounts.account_close') }}">انشاء اقفال</a>

@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>القيمة</th>
                    <th>العجز</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactionContainer as $container)
                    <tr>
                        <td>{{ $container->created_at }}</td>
                        <td>{{ $container->amount }}</td>
                        <td>
{{--                            @if(empty())--}}
{{--                                {{ $container->transactions()->where('description','close_account')->sum('amount') }}--}}
{{--                            @endif--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                {{ $transactionContainer->links() }}
            </table>
        </div>
    </div>
@endsection