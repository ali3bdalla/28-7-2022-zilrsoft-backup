<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	/**
	 * @property mixed shipping_method_id
	 * @property mixed shipping_address_id
	 * @property mixed draft_id
	 * @property mixed net
	 * @property mixed|string status
	 * @property mixed user_id
	 * @property mixed auto_cancel_at
	 * @property false|mixed is_should_pay_notified
	 * @property false|mixed should_pay_last_notification_at
	 * @property mixed itemsQtyHolders
	 */
	class Order extends BaseModel
	{
		use SoftDeletes;
		
		
		protected $guarded;
		
		public function user()
		{
			return $this->belongsTo(User::class);
		}
		
		
		public function itemsQtyHolders()
		{
			return $this->hasMany(OrderItemQtyHolder::class,'order_id');
		}
		public function shippingAddress()
		{
			return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
		}
		
		public function shippingMethod()
		{
			return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
			
		}
		
		public function generatePayOrderUrl()
		{
			return file_get_contents('http://tinyurl.com/api-create.php?url=' . 'https://www.zilrsoft.com/web/orders/'  . $this->id . '/confirm_payment');
		}
		
		public function generateCancelOrderUrl()
		{
			return file_get_contents('http://tinyurl.com/api-create.php?url=' . 'https://www.zilrsoft.com/web/orders/'  . $this->id . '/cancel');
		}
		
		
	}
