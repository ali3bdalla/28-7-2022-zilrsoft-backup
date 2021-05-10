<?php

namespace App\Models;

use App\Models\Traits\Configurable;
use App\Models\Traits\UserBalanceTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 * @property mixed balance
 * @property mixed vendor_balance
 * @property mixed is_system_user
 * @property mixed country_code
 * @property mixed phone_number
 */
class User extends BaseAuthModel
{

	use SoftDeletes;
	use Configurable;
	use UserBalanceTrait;
	protected $guarded = [];




	protected $appends = [
		'locale_name',
		'international_phone_number'
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


	public function orders()
	{
		return $this->hasMany(Order::class,'user_id');
	}
	public function getYearlyBalance(Account $account)
	{
		$creditAmount = $account->transactions()->where(
			[
				['type', 'credit'],
				['user_id', $this->id]
			]
		)->sum('amount');

		$debitAmount = $account->transactions()->where(
			[
				['type', 'debit'],
				['user_id', $this->id]
			]
		)->sum('amount');

		if ($account->type == 'credit')
			return $creditAmount - $debitAmount;


		return $debitAmount - $creditAmount;
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class, 'user_id');
	}

	public function getNameAttribute($value)
	{

		return $value;
		//			return $this->name;
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

	public function getInternationalPhoneNumberAttribute()
	{
//        $this->country_code
        if(!app()->environment('production'))
            return '249966324018';
		return '966' . $this->phone_number; // 0966324018
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
		return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
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
