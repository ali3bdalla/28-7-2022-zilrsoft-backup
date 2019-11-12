@extends('layouts.master2')



@section('title',__('sidebar.items'))
@section('desctipion',__('pages/items.create'))
@section('route',route('management.items.index'))

@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/items'))'
    </script>
@stop

@section('content')
<div class="">
	<div class="row">
		<div class="col-md-12">
			<article class="message  is-info">
				@if(isset($isEdited))
					<new-item-form-component :categories='@json($categories)' :is-edited="true"
											 :is-cloned="true"
											 :item="{{$item}}"
											 :item-filters='@json($item->filters)' :item-category='@json($item->category)'></new-item-form-component>
				@elseif($isClone)
					<new-item-form-component :categories='@json($categories)' :is-edited="false"
                                             :is-cloned="true"
                                             :item="{{$item}}"
                                             :item-filters='@json($item->filters)' :item-category='@json($item->category)'></new-item-form-component>
				@else
					<new-item-form-component :categories='@json($categories)' :is-cloned="false"></new-item-form-component>
				@endif


			</article>
		</div>
	</div>
</div>

@stop
