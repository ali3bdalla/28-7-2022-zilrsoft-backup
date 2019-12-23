<?php
	
	
//	auth()->loginUsingId(1);
	Route::get('/',function (){
		return redirect(route('accounting.dashboard.index'));
	});
	
	
	Route::get('/locale','LocaleController');
