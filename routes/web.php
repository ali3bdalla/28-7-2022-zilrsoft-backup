<?php
	
	
	app()->setLocale('ar');
	
Route::get('/', function(){
	return redirect('/management/login');
});


Route::get('/locale', 'LocaleController');
