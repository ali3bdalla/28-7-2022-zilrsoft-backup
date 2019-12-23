@extends('layouts.master2')



@section('title',__('sidebar.barcode'))
@section('desctipion',__('pages/items.barcode'))
@section('route',route('management.items.barcode.index'))

@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/items'))'
    </script>
@stop

@section('content')
    <div class="box">
        <form method="get" rel="serial_form" action="{{ route('management.items.barcode.show') }}">
            <div class="field">
                <input class="input" name="barcode" value="{{ old('barcode') }}" placeholder="ادخل الباركود"
                       @keyup.enter="this.$refs.serial_form.submit()"
                ></input>
            </div>
            @error('serial')
            <p class="help is-danger">{{ $message }}</p>
            @enderror
            <div class="form-group">
                <button class="button is-primary" type="submit">{{ __('pages/serial.show_history') }}</button>
            </div>
        </form>
    </div>
@stop
