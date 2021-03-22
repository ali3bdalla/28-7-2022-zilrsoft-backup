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
            <h4 class="d-flex justify-content-center align-items-center mb-3">
                {{-- <span class="text-muted">Orders</span> --}}
              <span class="badge badge-secondary badge-pill">  عدد الشحنات  ({{$transactions->count()}})</span>
            </h4>

            <div class="list-group mb-3">
                @foreach($transactions as $transaction)
                    <div class="list-group-item"
                        @if($transaction->status == 'received')
                        style="background-color: #84e6d3"
                            @endif
                    >
                        <div>
                            <h6 class="my-0">{{$transaction->first_name}} {{$transaction->last_name}}</h6>
                            <small class="text-muted"><a href="tel:0{{$transaction->phone_number}}">0{{$transaction->phone_number}}</a> </small>
                            <small class="text-muted">{{$transaction->created_at}} </small>
                        </div>
                        <div>
                             {{ $transaction->address }}
                        </div>
                        @if($transaction->status !== 'received')
                        <div style="margin-top: 9px">
                            <confirm-transaction-delivered :delivery-man='@json($deliveryMan)' :transaction='@json($transaction)'></confirm-transaction-delivered>
                        </div>
                        @endif
                    </div>
                @endforeach
               {{ $transactions->links() }}
        </div>

    </div>


</div>

</body>
</html>
