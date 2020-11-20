<?php
	
	
	use Illuminate\Support\Facades\Broadcast;
	
	
	Broadcast::channel(
		'transaction-issued', function($user) {
		return $user !== null;
	}
	);