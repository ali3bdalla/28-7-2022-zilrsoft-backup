@extends('layouts.web.master')

@section('content')
    @includeIf('web.components.items.sliderCollections')
    @includeIf('web.components.categories.gridCollection')
@endsection
