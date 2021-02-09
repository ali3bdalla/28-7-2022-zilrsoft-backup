@extends('accounting.layout.master')

@if(!$isClone)
    @section('title',__('pages/items.edit') . ' | ' .  $item->locale_name)
@else
    @section('title',__('pages/items.clone') . ' | ' .  $item->locale_name)
@endif

@section("before_content")

@endsection




@section("content")

    <accounting-items-create-component
            :editing-item="true"
            :cloning-item="{{ $isClone }}"
            :edited-item-data="{{$item->load('attachments')}}"
            :edited-item-filters='@json($item->filters)'
            :edited-item-category='@json($item->category)'
            :can-create-category="true"
            :can-create-filter="true"
            :can-edit-filter="true"
            :vendors='@json($vendors)'
            :categories='@json($categories)'
            :is-cloned="false"
            
    >
    </accounting-items-create-component>
    {{-- :edited-item-category='@json($item->category)'
    :can-create-category="{{ auth()->user()->canDo('create category') }}"
    :can-create-filter="{{ auth()->user()->canDo('create filter') }}"
    :can-edit-filter="{{ auth()->user()->canDo('edit filter') }}" --}}
@endsection





@section("after_content")
@endsection