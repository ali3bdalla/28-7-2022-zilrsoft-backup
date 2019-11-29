@extends('layouts.master2')

@section('title',__('sidebar.financial_statements'))
@section('route',route('management.financial_statements.index'))


@section('content')
<div class="box">
    <div class="panel">
        <div class="card" style="width: 18rem;">
            <div class="panel-body text-center">
{{--                <h5 class="card-title">Card title</h5>--}}
                <a href="{{ route('management.financial_statements.trail_balance') }}" class="btn btn-primary">ميزان المراجعة</a>
            </div>
        </div>
    </div>
</div>
@endsection
