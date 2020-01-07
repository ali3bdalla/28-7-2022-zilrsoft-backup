<?php
	
	
//	auth()->loginUsingId(1);
	Route::get('/',function (){
		return redirect(route('accounting.dashboard.index'));
	});
	
	



	Route::match(
		['post','get','delete','put','patch'],
		'check_method',
		function(\Symfony\Component\HttpFoundation\Request $request){
		return $request->getMethod();
	});
	
	
	
	
	Route::resource('users','UsersController');
	Route::post('users/profile/view',"UsersController@view_profile");
	