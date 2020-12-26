@extends('accounting.layout.master')


@section('title',__('sidebar.barcode'))


@section('content')

    <div class="panel panel-custom-form">
        <div class="panel-heading">
            <form method="get" rel="serial_form" action="{{ route('accounting.items.show_item_barcode') }}">
                <div class="form-group">
                    <input class="form-control" value="{{ old('barcode') }}" name="barcode"
                           placeholder="{{__('pages/items.barcode')}}"
                           @keyup.enter="this.$refs.serial_form.submit()"/>
                </div>
                @error('barcode')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
                <div class="form-group text-center">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <button class="btn btn-custom-primary btn-block" type="submit">{{ __('pages/items.show')
                    }}</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
@stop
