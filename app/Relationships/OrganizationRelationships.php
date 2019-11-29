<?php
	
	namespace App\Relationships;
	
	use App\Account;
	use App\Branch;
	use App\Category;
	use App\Country;
	use App\Department;
	use App\Expense;
	use App\Filter;
	use App\Gateway;
	use App\GatewayAccounts;
	use App\Invoice;
	use App\Item;
	use App\OrganizationGateway;
	use App\Payment;
	use App\SaleInvoice;
	use App\TransactionsContainer;
	use App\User;
	
	trait OrganizationRelationships
	{
		
		public function country()
		{
			return $this->belongsTo(Country::class,'country_id');
		}
		
		public function categories()
		{
			return $this->hasMany(Category::class,'organization_id');
		}
		
		public function items()
		{
			return $this->hasMany(Item::class,'organization_id');
		}
		
		public function invoices()
		{
			return $this->hasMany(Invoice::class,'organization_id');
		}
		
		public function expenses()
		{
			return $this->hasMany(Expense::class,'organization_id');
		}
		
		public function branches()
		{
			return $this->hasMany(Branch::class,'organization_id');
		}
		
		public function departments()
		{
			return $this->hasMany(Department::class,'organization_id');
		}
		
		public function kits()
		{
			return $this->hasMany(Item::class,'organization_id');
		}
		
		public function sales()
		{
			return $this->hasMany(SaleInvoice::class,'organization_id');
		}
		
		public function payments()
		{
			return $this->hasMany(Payment::class,'organization_id');
		}
		
		public function users()
		{
			return $this->hasMany(User::class,'organization_id');
		}
		
		public function filters()
		{
			return $this->hasMany(Filter::class,'organization_id');
		}
		
		public function supervisor()
		{
			return $this->belongsTo(User::class,'supervisor_id');
		}
		
		public function accounts()
		{
			return $this->hasMany(Account::class,'organization_id');
		}
		
		public function transactions_containers()
		{
			return $this->hasMany(TransactionsContainer::class,'organization_id');
		}
		
	}
