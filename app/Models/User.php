<?php
	
	namespace App\Models;
	
	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	/**
	 * @property mixed id
	 * @property mixed balance
	 * @property mixed vendor_balance
	 */
	class User extends BaseAuthModel
	{
		
		use SoftDeletes;
		
		protected $guarded = [];
		
	
		protected $appends = [
			'locale_name'
		];
		protected $casts = [
			'is_vendor' => 'boolean',
			'is_client' => 'boolean',
			'is_supplier' => 'boolean',
			'can_make_credit' => 'boolean',
			'is_supervisor' => 'boolean',
			'is_manager' => 'boolean',
		];
		
		protected $hidden = [
			'password'
		];
		
		public function invoices()
		{
			return $this->hasMany(Invoice::class, 'user_id');
		}
		
		public function getNameAttribute()
		{
			
			return $this->name_ar;
		}
		
		public function getLocaleNameAttribute()
		{
			
			return $this->name_ar;
		}
		
		
		public function shippingAddresses()
		{
			return $this->hasMany(ShippingAddress::class, 'user_id');
		}
		
		public function isSystemUser()
		{
			return $this->is_system_user;
		}
		
		public function _getClientBalance()
		{
			return $this->balance;
		}
		
		public function _getVendorBalance()
		{
			return $this->getOriginal('vendor_balance');
		}
		
		
		public function _isClient()
		{
			return $this->is_client == true;
		}
		
		public function _isVendor()
		{
			return $this->is_vendor == true;
		}
		
		
		public function _isManager()
		{
			return $this->is_manager == true;
		}
		
		
		public function getCreatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateString();
		}
		
		
		public function details()
		{
			return $this->hasOne(UserDetails::class, 'user_id');
		}
		
		public function organization()
		{
			return $this->belongsTo(Organization::class, 'organization_id');
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class, 'creator_id');
		}
		
		public function manager()
		{
			return $this->hasOne(Manager::class, 'user_id');
		}
		
		public function gateways()
		{
			return $this->hasMany(UserGateways::class, 'user_id');
		}
		
		
	}

