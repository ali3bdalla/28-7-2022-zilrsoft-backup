<?php
	
	namespace App\Models;
	
	
	/**
	 * @property mixed order_id
	 * @property mixed sender_account_id
	 * @property mixed received_bank_id
	 * @property mixed first_name
	 * @property mixed last_name
	 * @property mixed user_id
	 */
	class OrderPaymentDetail extends BaseModel
	{
		//
		protected $guarded = [];
		
		public function order()
		{
			return $this->belongsTo(Order::class, 'order_id');
		}
		
		public function senderAccount()
		{
			return $this->belongsTo(UserGateways::class, 'sender_account_id');
		}
		
		
		public function receivedBank()
		{
			return $this->belongsTo(Bank::class, 'received_bank_id');
		}
	}
