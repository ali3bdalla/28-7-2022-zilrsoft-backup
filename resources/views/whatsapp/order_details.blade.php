Dear ~{{$client->name}}~,

Thank you for your order

Order# {{$order->id}}

*Amount: {{$order->net}}*

Banks Accounts:

    Rajhi:   *38238258237951*

    Alahli:  *54564646546546*

    Albilad: *23749823749873*

Deadline for Payment {{$deadline}}

Please Fill This Form After Transfer
{{ $order->generateBillingUrl() }}

{{$client->organization->getTranslate('title','en')}}