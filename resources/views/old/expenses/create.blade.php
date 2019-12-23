@extends('old.layouts.master2')


@section('title',__('pages/expenses.create'))
@section('desctipion','')
@section('route',route('management.expenses.index'))


@section('content')

    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        {{ __('pages/expenses.create') }}
                    </div>

                    <form method="post" action="{{ route('management.expenses.store')}}">
                        @csrf
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">

                                    <div class="column">
                                        <input
                                                class="input"
                                                value="{{ old('name') }}"

                                                name="name"
                                                @error('name')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                placeholder='{{ __('reusable.name') }}'
                                                mode='en'/>

                                    </div>
                                    <div class="column">
                                        <input
                                                class="input"
                                                value="{{ old('ar_name') }}"
                                                @error('ar_name')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                placeholder='{{ __('reusable.ar_name') }}'
                                                mode='ar'
                                                name='ar_name'/>
                                    </div>
                                </div>


                                <div class="columns">
                                    <div class="column">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" data-toggle="toggle"checked
                                                       name="appear_in_sale">
                                                يظهر في المبيعات
                                            </label>
                                        </div>

                                    </div>
                                    <div class="column">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" data-toggle="toggle"checked
                                                       name="appear_in_purchase">
                                                يظهر في المشتريات
                                            </label>
                                        </div>

                                    </div>
                                </div>


                                <div class="form-group">
                                    <button class="button is-success"><i class="fa fa-check-circle"></i>&nbsp; {{ __
                                    ('reusable.create') }}</button>
                                    &nbsp;
                                    <a href="{{ route('management.expenses.index') }}" class="button is-right"><i
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
