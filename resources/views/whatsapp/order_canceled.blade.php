Dear Customer {{$order->user->name}},
Sorry, Your Order #{{$order->id}} has been cancelled

Cancelled Reasons:
@if($isManual)
1- At Your Request .
@else
1- Not Paid .
@endif

Customer Service (WhatsApp)
Wa.me/065433