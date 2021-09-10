<?php

namespace App\Http\Controllers\App\API\Notifications;

use App\Http\Controllers\Controller;
use App\Models\ResellerClosingAccount;
use App\Scopes\DraftScope;
use App\Scopes\PendingScope;
use Illuminate\Http\Request;

class TransactionNotificationController extends Controller
{
    public function issued(Request $request)
    {
        return ResellerClosingAccount::where([['is_pending', true], ['transaction_type', 'transfer'], ['receiver_id', $request->user()->id]])->with('creator', 'receiver', 'fromAccount', 'toAccount')
            ->withoutGlobalScopes([DraftScope::class, PendingScope::class])->get();
    }
}
