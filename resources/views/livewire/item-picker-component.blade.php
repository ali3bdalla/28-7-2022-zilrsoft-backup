<div>
    <div>
        <input id="items-selector" value="{{$search}}" wire:keydown.enter="$set('search',$event.target.value)" class="form-control" />
        @error('invalidPickedItem')
            <span class="text-danger">{{ $message}}</span>
        @enderror
    </div>
    @if($this->items)
    <div class="list-group">
        @foreach($this->items as $item)
        <button type="button" class="list-group-item text-right" wire:click="broadcastPickedItem({{$item}})">
            {{ $item->locale_name }}
            <span class="badge badge-primary">{{ $item->barcode }} - {{ round($item->price_with_tax,2) }} SAR</span>
        </button>
        @endforeach
    </div>
    @endif
</div>
@push("scripts")
    <script>
        $(document).ready(function(){
          $("#items-selector").focus();
        })
    </script>
@endpush
