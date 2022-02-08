<div class="form-group">
    <h4 class="">{{__("pages/invoice.client")}}</h4>
    <select class="js-example-basic-single form-group"  wire:model="selectedCustomer">
        <option></option>
        @foreach($this->customers as $customer)
            <option value="{{$customer->id}}">{{$customer->locale_name}}</option>
        @endforeach
    </select>

</div>
