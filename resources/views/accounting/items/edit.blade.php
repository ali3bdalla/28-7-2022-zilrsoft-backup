@extends('accounting.layout.master')

@section('title',__('pages/items.edit') . ' | ' .  $item->locale_name)



@section("before_content")

@endsection




@section("content")

    <accounting-items-create-component
            :editing-item="true"
            :cloning-item="{{ $isClone }}"
            :edited-item-data="{{$item}}"
            :edited-item-filters='@json($item->filters)'
            :edited-item-category='@json($item->category)'
            :can-create-category="{{ auth()->user()->canDo('create category') }}"
            :can-create-filter="{{ auth()->user()->canDo('create filter') }}"
            :can-edit-filter="{{ auth()->user()->canDo('edit filter') }}"
            :vendors='@json($vendors)'
            :categories='@json($categories)'
            :is-cloned="false"
    >
    </accounting-items-create-component>
@endsection





@section("after_content")
@endsection