@extends('accounting.layout.master')

@section('title',__('pages/users.edit') . " | "  . $manager->locale_name)



@section("content")
    <accounting-managers-create-component
            :editing-manager="true"
            :manager='@json($manager)'
            :manager-user='@json($manager->user)'
            :manager-department='@json($manager->department)'
            :manager-branch='@json($manager->branch->load('departments'))'
            :manager-permissions='@json($manager_permissions)'
            :manager-gateways='@json($manager_gateways)'
            :branches='@json($branches)'>

    </accounting-managers-create-component>
@endsection