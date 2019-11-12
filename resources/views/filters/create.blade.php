@extends('layouts.master2')



@section('title',__('pages/filters.create'))
@section('desctipion','')
@section('route',route('management.filters.index'))


@section('content')
    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        {{ __('pages/filters.create') }}
                    </div>

                    <form method="post" action="{{ route('management.filters.store')}}">
                        @csrf
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">

                                    <div class="column">
                                        <input-text-component name='name'
                                                              value="{{ old('name') }}"

                                                              @error('name')
                                                              :has-error="true"
                                                              :error-message='@json($message)'
                                                              @enderror
                                                              placeholder='{{ __('reusable.name') }}'
                                                              mode='en'></input-text-component>

                                    </div>
                                    <div class="column">
                                        <input-text-component
                                               
                                                value="{{ old('ar_name') }}"
                                               

                                                @error('ar_name')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                placeholder='{{ __('reusable.ar_name') }}' mode='ar'
                                                name='ar_name'></input-text-component>
                                    </div>
                                </div>

                               

                                


                                <div class="form-group">
                                    <button class="button is-success"><i class="fa fa-check-circle"></i>&nbsp; {{ __
                                    ('reusable.create') }}</button>
                                    &nbsp;
                                    <a href="{{ route('management.filters.index') }}" class="button is-right"><i 
                                                class="fa fa-undo-alt"></i>&nbsp; {{ __('reusable.cancel') }}</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </article>
            </div>
        </div>
    </div>

@endsection
