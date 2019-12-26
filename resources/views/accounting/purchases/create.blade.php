@extends('accounting.layout.master')
@section('title',__('pages/invoice.purchase'))



@section('content')
    <accounting-purchases-create-component
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :expenses='@json($expenses)'
            :vendors='@json($vendors)'
            :gateways='@json($gateways)'
            :receivers='@json($receivers)'
            :creator='@json(auth()->user()->with('department','branch')->first())'
    ></accounting-purchases-create-component>
@endsection