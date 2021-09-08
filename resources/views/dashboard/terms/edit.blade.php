@extends('dashboard.layouts.app')

@section('content')
    @include('adminlte-templates::common.errors')
   <v-card>
       {!! Form::model($term, ['route' => ['dashboard.banners.update', $term->id], 'ref' => 'dashForm', '@submit.prevent' => 'validateBeforeSubmit', 'method' => 'patch', 'files' => true]) !!}
       <v-card-header
           title="{{ trans('dashboard.buttons.edit') }} {!! $term->name !!}"
           color="primary">
       </v-card-header>
        <v-card-text>
            @include('dashboard.terms.fields')
        </v-card-text>
        <v-card-actions>
            <v-btn type="submit" color="primary">{{ trans('dashboard.buttons.save') }}</v-btn>
            <v-btn href="{!! route('dashboard.terms.index') !!}">{{ trans('dashboard.buttons.cancel') }}</v-btn>
        </v-card-actions>
        {!! Form::close() !!}
    </v-card>

@endsection
