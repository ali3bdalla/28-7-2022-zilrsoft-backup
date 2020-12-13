Dear ~{{$client->name}}~,

Thank you for your order

Order# {{$order->id}}

Amount: *{{displayMoney($order->net)}}*

Banks Accounts:

    Rajhi:   *38238258237951*

    Alahli:  *54564646546546*

    Albilad: *23749823749873*

    Deadline for Payment {{$deadline}}


Please Fill This Form After Transfer
{{ $order->generatePayOrderUrl() }}


To Cancel Your Order Please Click Flowing Link and use this code {{$order->cancel_order_code}}:
{{ $order->generateCancelOrderUrl() }}


{{ $invoice->organization->title}}