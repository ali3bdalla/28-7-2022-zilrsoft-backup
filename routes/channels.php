<?php
	
	
	use App\Broadcasting\OrderChannel;
	use Illuminate\Support\Facades\Broadcast;
	
	
	Broadcast::channel(
		'order.issued', function($user) {
		return $user->can('manage branches');
	}
	);
	