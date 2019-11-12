@extends('layouts.master2')


@section('title',__('sidebar.serial_history'))
@section('desctipion',__('sidebar.serial_history'))
@section('route',route('management.serial_history.index'))


@section('content')

    <div class="box">
        <form method="get" rel="serial_form" action="{{ route('management.serial_history.show') }}">
            <div class="field">
                <input class="input" name="serial" placeholder="{{__('pages/serial.serial')}}"
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
