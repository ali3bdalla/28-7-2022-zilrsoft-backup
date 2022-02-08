<div class="form-group">
    <h4 class="">{{__("pages/invoice.client")}}</h4>
{{--    js-example-basic-single--}}
    <select class="form-group"  wire:model="selectedCustomer" name="customerId">
        <option></option>
        @foreach($this->customers as $customer)
            <option value="{{$customer->id}}">{{$customer->locale_name}}</option>
        @endforeach
    </select>

</div>
