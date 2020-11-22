A reminder that the payment deadline for order number #{{$order->id}} is soon.

Please pay before #{{Carbon\Carbon::parse($order->auto_cancel_at)->format('H:i a')}}