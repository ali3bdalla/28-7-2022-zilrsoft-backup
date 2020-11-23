<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Http\Controllers\Controller;
use App\Models\ResellerClosingAccount;
use Illuminate\Http\Request;

class TransactionNotificationController extends Controller
{
	public function issued(Request $request)
	{
		return ResellerClosingAccount::toMe($request)->get();
	}
}
