@extends('limit.layout.master')

@section('title')
    {{ $item->ar_name }}
@endsection
@section("content")
    <div class="card">
        <div class="card-header text-right">{{$item->ar_name}}  ({{ $item->attachments->count() }}) صورة</div>
        <div class="card-body">
            <accounting-attachments-preview-component :attachments='@json($item->attachments)'
                                                      new_attachment_link='/limit/items/{{$item->id}}'></accounting-attachments-preview-component>
        </div>
    </div>

@endsection
