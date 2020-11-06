<?php
	
	use \App\Models\Category;
	use App\Models\Order;
	use Illuminate\Support\Facades\Broadcast;
	
	/*
	|--------------------------------------------------------------------------
	| Broadcast Channels
	|--------------------------------------------------------------------------
	|
	| Here you may register all of the event broadcasting channels that your
	| application supports. The given channel authorization callbacks are
	| used to check if an authenticated user can listen to the channel.
	|
	*/
	
	// Broadcast::channel('App.User.{id}',function ($user,$id){
	// 	return (int)$user->id === (int)$id;
	// });
	
	// Broadcast::channel("presence-my_channel",function (){
	// 	return true;
	// });
	
	// Broadcast::channel('category.{categoryId}',function ($user,$categoryId){
	// 	return $user->organization_id === Category::findOrNew($categoryId)->organization_id;
	// });
	
	//Broadcast::channel('test_broadcast',function ($user){
	//
	//});
	
	
	Broadcast::channel(
		'orderChannel', function($user) {
//		$user->id === Order::findOrNew($orderId)->user_id
		return true;
	}
	);
	