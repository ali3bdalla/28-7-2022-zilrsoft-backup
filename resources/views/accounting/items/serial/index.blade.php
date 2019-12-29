@extends('accounting.layout.master')


@section('title',__('sidebar.serial_history'))


@section('content')

    <div class="panel panel-custom-form">
        <div class="panel-heading">
            <form method="get" rel="serial_form" action="{{ route('accounting.items.show_serial_activities') }}">
                <div class="form-group">
                    <input class="form-control" name="serial"  value="{{ old('serial') }}" placeholder="{{__('pages/serial.serial')}}"
                           @keyup.enter="this.$refs.serial_form.submit()" />
                </div>
                @error('serial')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
                <div class="form-group text-center">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <button class="btn btn-custom-primary btn-block" type="submit">{{ __('pages/serial.show_history')
                    }}</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
@stop
