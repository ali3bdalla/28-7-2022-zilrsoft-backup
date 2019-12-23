@extends('old.layouts.master2')


@section('title',__('pages/accounts.creation'))
@section('route',route('management.accounts.index'))


@section('content')
    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        {{ __('pages/accounts.creation') }}
                    </div>

                    <form method="post" action="{{ route('management.accounts.store')}}">
                        @csrf
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">
                                    @if($isClone)
                                        <input type="hidden" name='cloned_account' value="{{$account->id}}">
                                    @endif
                                    <div class="column">
                                        <input-text-component name='name'
                                                              @if(empty(old('name')) && $isClone)
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
                                                @if(empty(old('ar_name')) && $isClone)
                                                value="{{ $account->ar_name }}"
                                                @else
                                                value="{{ old('ar_name') }}"
                                                @endif
                                                @error('ar_name')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                placeholder='{{ __('pages/categories.ar_name') }}' mode='ar'
                                                name='ar_name'></input-text-component>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">

                                        <div class="select is-fullwidth">
                                            <select name="parent_id">
                                                @foreach($accounts as $account)
                                                    <option value="{{$account['id']}}"
                                                    @if(\Illuminate\Support\Facades\Request::input("parent_id")
                                                    ==$account['id'])
                                                        selected
                                                        @endif
                                                    >{{$account['ar_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            هل هذه بوابة دفع ؟
                                        </div>
                                        <div class="col-md-3">
                                            <toggle-button name="is_gateway" font-size="19" height='30' width='70'
                                                           :labels="{checked: 'نعم', unchecked: 'لا'}"

                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="button is-success"><i
                                                class="fa fa-check-circle"></i>&nbsp; {{ __('reusable.create') }}
                                    </button>
                                    &nbsp;
                                    <input type="hidden" name="isClone" value="{{$isClone}}"/>
                                    <a href="{{ route('management.accounts.index') }}"
                                       class="button is-right"><i
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
