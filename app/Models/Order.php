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
	 */
	class Order extends BaseModel
	{
		use SoftDeletes;
		
		
		protected $guarded;
		
		public function user()
		{
			return $this->belongsTo(User::class);
		}
		
		public function shippingAddress()
		{
			return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
		}
		
		public function shippingMethod()
		{
			return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
			
		}
		
		public function generateBillingUrl()
		{
			return file_get_contents('http://tinyurl.com/api-create.php?url=' . 'https://www.zilrsoft.com/web/orders/' . base64_encode($this->id));
		}
		
		
	}
