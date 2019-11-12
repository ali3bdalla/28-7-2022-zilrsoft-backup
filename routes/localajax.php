<?php




Route::resource('category','CategoryController')->only([
	'create','destroy','update'
]);