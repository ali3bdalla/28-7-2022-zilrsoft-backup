@extends('old.layouts.master2')


@section('title',__('pages/categories.edit'))
@section('desctipion','categories')
@section('route',route('management.categories.index'))


@section('content')
    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        {{ __('pages/accounts.edit') }}
                    </div>

                    <form method="post" action="{{ route('management.accounts.update',$account->id)}}">
                        @csrf
                        @method('PATCH')
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">
                                    <div class="column">
                                        <input-text-component name='name'
                                                              @if(empty(old('name')))
                                                              value="{{ $account->name }}"

                                                              @else
                                                              value="{{ old('name') }}"
                                                              @endif

                                                              @error('name')
                                                              :has-error="true"
                                                              :error-message='@json($message)'
                                                              @enderror
                                                              placeholder='{{ __('pages/categories.name') }}'
                                                              mode='en'></input-text-component>

                                    </div>
                                    <div class="column">
                                        <input-text-component
                                                @if(empty(old('ar_name')))
                                                value="{{ $account->ar_name }}"
                                                @else
                                                value="{{ old('ar_name') }}"
                                                @endif
                                                @error('ar_name')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                placeholder='{{ __('pages/categories.ar_name') }}'
                                                mode='ar'
                                                name='ar_name'></input-text-component>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <select-list-component
                                                :items='@json($accounts)'
                                                capture-text='locale_name'

                                                @if(empty(old("parent_id")) && !empty($account))

                                                selected="{{$account->parent_id}}"


                                                @else
                                                selected=value="{{ old('parent_id') }}"
                                                @endif

                                                :has-default="true"
                                                default-capture="{{ __('pages/categories.main_account') }}"
                                                default-value='1'

                                                @error('parent_id')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                name='parent_id'></select-list-component>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            هل هذه بوابة دفع ؟
                                        </div>
                                        <div class="col-md-3">
                                            @if($account->is_gateway)
                                                <toggle-button name="is_gateway" :font-size="19" :height='30'
                                                               :width='70'
                                                               :labels="{checked: 'نعم', unchecked: 'لا'}"
                                                               :value="true"
                                                />
                                            @else
                                                <toggle-button name="is_gateway" :font-size="19" :height='30'
                                                               :width='70'
                                                               :labels="{checked: 'نعم', unchecked: 'لا'}"
                                                               :value="false"
                                                />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="button is-success"><i class="fa fa-check-circle"></i>&nbsp; {{ __
                                    ('pages/categories.update') }}
                                    </button>
                                    &nbsp;

                                    <a href="{{ route('management.accounts.index') }}" class="button is-right"><i
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
