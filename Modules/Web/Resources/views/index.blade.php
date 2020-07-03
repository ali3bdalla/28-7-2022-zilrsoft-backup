@extends('web::layouts.master')

@section('content')
    @includeIf('web::items.layout.sliderCollections')
    @includeIf('web::categories.layout.gridCollection')
@endsection
