<?php
	
	namespace App\Http\Controllers\Api\Notifications;
	
	use App\Http\Controllers\Controller;
	use App\Models\ResellerClosingAccount;
	use Illuminate\Http\Request;
	
	class TransactionNotificationController extends Controller
	{
		public function issued(Request $request)
		{
			return ResellerClosingAccount::where([['is_pending', true], ['transaction_type', 'transfer'], ['receiver_id', $request->user()->id]])->with('creator', 'receiver','fromAccount','toAccount')->withoutGlobalScopes(['draft','pending'])->get();
		}
	}
