@extends('accounting.layout.master')

@section('title',__('pages/invoice.view') . ' | '. $invoice->invoice_number )

@section('page_css')
    @if($invoice->is_draft)
        <style>
            .navbar {
                background-color: #b8b83a !important;
            }
        </style>
    @endif
@endsection


@section('buttons')

        <a href="{{route('accounting.printer.a4',$invoice->id)}}" target="_blank" class="btn btn-default">
            <i class="fa fa-print"></i> {{ __('pages/invoice.price_a4') }}
        </a>

        <accounting-print-receipt-layout-component
                :invoice-id="{{$invoice->id}}"></accounting-print-receipt-layout-component>

@stop


@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.client') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{
                               $invoice->user_alice_name=="" ?$invoice->user->locale_name:
                               $invoice->user_alice_name}}">

                    </div>
                </div>
                <div class="col-md-2">
                    <invoice-alice-name-form-pop :invoice-id="{{$invoice->id}}"
                                                 alice-name="{{$invoice->user_alice_name}}"></invoice-alice-name-form-pop>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.created_at') }}</span>
                        <input type="text" name="" disabled="disabled"
                               value="{{ $invoice->created_at }}"
                               class="form-control date_field_center">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.salesman') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->manager->locale_name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">الحالة</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->status->label }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            @includeIf('accounting.include.invoice.view_items',[
                 'items' => $invoice->items()->withoutGlobalScope(App\Scopes\DraftScope::class)->get()
            ])
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">التاريخ</th>
                    <th scope="col">المستخدم</th>
                    <th scope="col">الحالة</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->warrantyTracingHistories()->get() as $warrantyHistory)
                    <tr>
                        <th scope="row">{{$warrantyHistory->created_at}}</th>
                        <td>{{ $warrantyHistory->creator->locale_name }}</td>
                        <td>{{ $warrantyHistory->status->label }}</td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>
        <div class="panel-footer">
            <form method="post" action="">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-control">
                            @foreach($statuses as $status => $label)
                                <option value="{{ $status }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" onclick="return confirm('هل انت متاكد؟');" type="submit">
                            تحديث الحالة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@stop
