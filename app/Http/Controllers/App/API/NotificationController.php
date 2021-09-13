<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ResellerClosingAccount;
use App\Scopes\DraftScope;
use App\Scopes\PendingScope;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function transactionIssued(Request $request)
    {
        return ResellerClosingAccount::where([['is_pending', true], ['transaction_type', 'transfer'], ['receiver_id', $request->user()->id]])->with('creator', 'receiver', 'fromAccount', 'toAccount')
            ->withoutGlobalScopes([DraftScope::class, PendingScope::class])->get();
    }

    public function orderPending(Request $request)
    {
        return Order::where('status', 'pending')->with('user', 'paymentDetail', 'draftInvoice')->get();
    }

    public function orderPaid(Request $request)
    {
        return Order::where('status', 'paid')->with('user', 'paymentDetail', 'draftInvoice')->get();
    }
}
