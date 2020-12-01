<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://icon-library.com/images/delivery-icon-png/delivery-icon-png-12.jpg">

    <title>ZilrSoft | Delivery Man Page</title>


    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script defer src="{{ asset('js/external.js') }}" type="text/javascript"></script>
    <!-- Custom styles for this template -->
</head>

<body class="bg-light">

<div class="container" id="app">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="https://icon-library.com/images/delivery-icon-png/delivery-icon-png-12.jpg"
             alt="" width="72" height="72">
        <h2>{{ $deliveryMan->first_name }} {{ $deliveryMan->last_name }}</h2>
    </div>

    <div class="row">
        <div class="col-12">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Orders</span>
                <span class="badge badge-secondary badge-pill">{{$orders->count()}}</span>
            </h4>
            <ul class="list-group mb-3">
                @foreach($orders as $order)
                    <li class="list-group-item d-flex justify-content-between lh-condensed"
                        @if($order->status == 'delivered')
                        style="background-color: #84e6d3"
                            @endif
                    >
                        <div>
                            <h6 class="my-0">{{$order->shippingAddress->first_name}} {{$order->shippingAddress->last_name}}</h6>
                            {{--                        <small class="text-muted">Brief description</small>--}}
                            <small class="text-muted">{{$order->shippingAddress->phone_number}}</small>
                        </div>
                        <div class="float-right text-lg">
                            <confirm-order-delivery-status :delivery-man='@json($deliveryMan)' :order='@json($order)'></confirm-order-delivery-status>

                        </div>
                    </li>
                @endforeach
                {{--                <li class="list-group-item d-flex justify-content-between lh-condensed">--}}
                {{--                    <div>--}}
                {{--                        <h6 class="my-0">Second product</h6>--}}
                {{--                        <small class="text-muted">Brief description</small>--}}
                {{--                    </div>--}}
                {{--                    <span class="text-muted">$8</span>--}}
                {{--                </li>--}}
                {{--                <li class="list-group-item d-flex justify-content-between lh-condensed">--}}
                {{--                    <div>--}}
                {{--                        <h6 class="my-0">Third item</h6>--}}
                {{--                        <small class="text-muted">Brief description</small>--}}
                {{--                    </div>--}}
                {{--                    <span class="text-muted">$5</span>--}}
                {{--                </li>--}}
                {{--                <li class="list-group-item d-flex justify-content-between bg-light">--}}
                {{--                    <div class="text-success">--}}
                {{--                        <h6 class="my-0">Promo code</h6>--}}
                {{--                        <small>EXAMPLECODE</small>--}}
                {{--                    </div>--}}
                {{--                    <span class="text-success">-$5</span>--}}
                {{--                </li>--}}
                {{--                <li class="list-group-item d-flex justify-content-between">--}}
                {{--                    <span>Total (USD)</span>--}}
                {{--                    <strong>$20</strong>--}}
                {{--                </li>--}}
            </ul>

            {{--            <form class="card p-2">--}}
            {{--                <div class="input-group">--}}
            {{--                    <input type="text" class="form-control" placeholder="Promo code">--}}
            {{--                    <div class="input-group-append">--}}
            {{--                        <button type="submit" class="btn btn-secondary">Redeem</button>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </form>--}}
        </div>

    </div>


</div>

</body>
</html>
