<?php


//	use Carbon\Carbon;
//	use Symfony\Component\HttpFoundation\Request;
//
	
//	use App\Events\Accounting\Invoice\PendingPurchaseInvoiceCreatedEvent;
//
//	event(new PendingPurchaseInvoiceCreatedEvent("hello message"));

//
//	Route::get('test_time',function (){
//		$start = Carbon::parse("10 Am");
//		$end = Carbon::parse("1 pm");
//		$range = $start->diffInMinutes($end);
//
////		$duration = 30;
////		for($i = $start;$i<$end->format('i');$i+$duration)
////		{
////
////		}
////		dd($range);
//	});
//
//
////	auth()->loginUsingId(1);
	Route::get('/',function (){
		return redirect(route('accounting.dashboard.index'));
	});
	//
	//
	//	Route::match(
	//		['post','get','delete','put','patch'],
	//		'check_method',
	//		function (Request $request){
	//			return $request->getMethod();
	//		});
	//
	//
	//	Route::resource('users','UsersController');
	//	Route::post('users/profile/view',"UsersController@view_profile");
	//