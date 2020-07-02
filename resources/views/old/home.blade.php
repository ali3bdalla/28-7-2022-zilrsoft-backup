@extends('old.layouts.master2')


@section('title', auth()->user()->organization->title_ar )
@section('route',route('management.dashboard'))




@section('content')
    <div class="col-md-12">
        hello world
    </div>
@endsection
