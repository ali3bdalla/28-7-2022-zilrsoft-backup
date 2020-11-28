<?php
	
	
	use App\Models\Manager;
	use Illuminate\Support\Facades\Broadcast;
	
	
	Broadcast::channel(
		'transaction-issued', function($user) {
		return $user !== null;
	}
	);
	
	
	Broadcast::channel(
		'order-payment-updated', function(Manager $user) {
		return $user->can('manage branches');
	}
	);
	
	Broadcast::channel(
		'order-payment-confirmed', function(Manager $user) {
		return !$user->can('manage branches');
	}
	);