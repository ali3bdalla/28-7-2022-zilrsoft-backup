<?php
	
	namespace App\Broadcasting;
	
	use App\Models\Manager;
	use App\Models\Order;
	
	class OrderChannel
	{
	
		/**
		 * Authenticate the user's access to the channel.
		 *
		 * @param Manager $user
		 * @param Order $order
		 * @return array|bool
		 */
		public function join(Manager $user)
		{
			return  true;
		}
	}
