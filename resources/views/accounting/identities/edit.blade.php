@extends('accounting.layout.master')

@section('title',__('pages/users.edit') . " | ". $identity->name)



@section("content")
    <accounting-identities-create-component
            :banks='@json($banks)'
            :editing-identity="true"
            :identity='@json($identity)'
            :identity-details='@json($identity->details)'
            :identity-gateways='@json($identity->gateways)'
    ></accounting-identities-create-component>
@endsection