<?php
	
	
	namespace App\Http\Views\Composers;
	
	
	use Illuminate\Contracts\Auth\Guard;
	use Illuminate\Contracts\View\View;
	
	class ShareLoggedUserComposer
	{
		
		
		public function compose(View $view)
		{
			if(auth('manager')->check()) {
				$view->with('loggedManager', auth('manager')->user());
			}

//			$view->with('count', $this->users->count());
		}
	}