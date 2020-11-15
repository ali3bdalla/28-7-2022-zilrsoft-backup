<?php
	
	
	namespace App\Http\Views\Composers;
	
	
	use App\Models\Manager;
	use App\Models\ResellerClosingAccount;
	use Illuminate\Contracts\Auth\Guard;
	use Illuminate\Contracts\View\View;
	
	class ShareLoggedUserComposer
	{
		
		
		public function compose(View $view)
		{
			if(auth('manager')->check()) {
				$manager = auth('manager')->user();
				$reseller = ResellerClosingAccount::where([['is_pending',true],['transaction_type','transfer'],
		        ['receiver_id',auth()->user()->id]])->with('creator','receiver')->get();
				$view->with('loggedManager', (Manager::find($manager->id))->load('department','branch','user')->toArray());
				$view->with('headerResellerData', $reseller->toArray());
//				dd(auth('manager')->user());
			}

		}
	}