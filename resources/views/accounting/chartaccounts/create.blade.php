@extends('layouts.master2')


@section('title',__('pages/categories.create'))
@section('desctipion','')
@section('route',route('accounts'))


@section('content')
    <div class="center-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <article class="message  is-success">
                    <div class="message-header">
                        {{ __('pages/categories.category_details') }}
                    </div>

                    <form method="post" action="{{ route('accounts')}}">
                        @csrf
                        <div class="message-body">
                            <div class="form-group">
                                <div class="columns">
                                    @if($isClone)
                                        <input type="hidden" name='cloned_category' value="{{$category->id}}">
                                    @endif
                                    <div class="column">
                                        <input-text-component name='name'
                                                              @if(empty(old('name')) && $isClone)
                                                              value="{{ $category->name }}"

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
                                                value="{{ $category->ar_name }}"

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

                                        <input-textarea-component
                                                @if(empty(old('description')) && $isClone)
                                                value="{{ $category->description }}"

                                                @else
                                                value="{{ old('description') }}"
                                                @endif


                                                @error('description')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                name='description'
                                                placeholder='{{ __('pages/categories.description') }}'
                                                mode='en'></input-textarea-component>

                                    </div>
                                    <div class="column">
                                        <input-textarea-component
                                                @if(empty(old('ar_description')) && $isClone)
                                                value="{{ $category->ar_description }}"

                                                @else
                                                value="{{ old('ar_description') }}"
                                                @endif
                                                @error('ar_description')
                                                :has-error="true"
                                                :error-message='@json($message)'
                                                @enderror
                                                placeholder='{{ __('pages/categories.ar_description') }}' mode='ar'
                                                name='ar_description'></input-textarea-component>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">

                                        <div class="select is-fullwidth">
											<select name="parent_id">


												@foreach($categories as $category)

													<option value="{{$category['id']}}">{{$category['ar_name']}}</option>
												@endforeach
											</select>
										</div>
                                        {{--									<select-list-component--}}
                                        {{--									:items='@json($categories)'--}}
                                        {{--									capture-text='locale_name'--}}
                                        {{--									--}}
                                        {{--									@if(empty(old("parent_id")) && !empty($category)) --}}
                                        {{--										@if($isClone)--}}
                                        {{--											selected="{{$category->parent_id}}"--}}
                                        {{--										@else--}}
                                        {{--											selected="{{$category->id}}"--}}
                                        {{--										@endif--}}
                                        {{--										--}}
                                        {{--									@else --}}
                                        {{--										selected=value="{{ old('parent_id') }}"--}}
                                        {{--									@endif--}}
                                        {{--									--}}
                                        {{--									:has-default="true"--}}
                                        {{--									default-capture="{{ __('pages/categories.main_category') }}"--}}
                                        {{--									default-value='1'--}}

                                        {{--									@error('parent_id')--}}
                                        {{--									:has-error="true"--}}
                                        {{--									 :error-message='@json($message)'--}}
                                        {{--									 @enderror--}}
                                        {{--									  name='parent_id'></select-list-component>	--}}
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button class="button is-success"><i class="fa fa-check-circle"></i>&nbsp; {{ __
								('reusable.create') }}</button>
                                    &nbsp;
                                    <input type="hidden" name="isClone" value="{{$isClone}}"/>
                                    <a href="{{ route('accounts') }}"
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
