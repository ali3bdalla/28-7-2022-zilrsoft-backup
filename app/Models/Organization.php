<?php

	namespace App\Models;

use App\Models\Traits\Configurable;
use Illuminate\Support\Facades\Storage;

/**
	 * @property mixed clients_chart_account_id
	 * @property mixed accounts_organization_id
	 * @property mixed accounts_creator_id
	 */
	class Organization extends BaseModel
	{


		use Configurable;
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];


		public function getLocaleDescriptionAttribute()
		{
			if(app()->isLocale('ar'))
			return $this->description_ar;

		return $this->description;
		}

		public function getLocaleTitleAttribute()
		{
			if(app()->isLocale('ar'))
			return $this->title_ar;

		return $this->title;
		}
		public function getOrganizationTaxAttribute()
		{
			return 1.15;
		}


		public function getOrganizationVatAttribute()
		{
			return 15;
		}
        public function getLogoAttribute($value)
        {
           return 'https://images.zilrsoft.com/api/insecure/fit/200/200/no/0/plain/local:///com.zilrsoft/storage/app/public/' . $value;
        }

        public function getStampAttribute($value)
        {
            return 'https://images.zilrsoft.com/api/insecure/fit/200/200/no/0/plain/local:///com.zilrsoft/storage/app/public/' . $value;
        }

		public function getLocalizedLogoAttribute()
		{
			if(app()->isLocale('ar'))
				return "https://zilrsoft.com/images/logo_ar.png";

			return "https://zilrsoft.com/images/logo_en.png";

		}
		public function country()
		{
			return $this->belongsTo(Country::class, 'country_id');
		}

		public function categories()
		{
			return $this->hasMany(Category::class, 'organization_id');
		}

		public function items()
		{
			return $this->hasMany(Item::class, 'organization_id');
		}

		public function invoices()
		{
			return $this->hasMany(Invoice::class, 'organization_id');
		}

		public function expenses()
		{
			return $this->hasMany(Expense::class, 'organization_id');
		}

		public function branches()
		{
			return $this->hasMany(Branch::class, 'organization_id');
		}

		public function departments()
		{
			return $this->hasMany(Department::class, 'organization_id');
		}

		public function kits()
		{
			return $this->hasMany(Item::class, 'organization_id');
		}

		public function sales()
		{
			return $this->hasMany(Sale::class, 'organization_id');
		}

		public function payments()
		{
			return $this->hasMany(Payment::class, 'organization_id');
		}

		public function users()
		{
			return $this->hasMany(User::class, 'organization_id');
		}

		public function filters()
		{
			return $this->hasMany(Filter::class, 'organization_id');
		}

		public function managers()
		{
			return $this->hasMany(Manager::class, 'organization_id');
		}

		public function supervisor()
		{
			return $this->belongsTo(User::class, 'supervisor_id');
		}

		public function accounts()
		{
			return $this->hasMany(Account::class, 'organization_id');
		}

		public function transactions_containers()
		{
			return $this->hasMany(TransactionsContainer::class, 'organization_id');
		}

	}
