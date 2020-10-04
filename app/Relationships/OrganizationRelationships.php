<?php

namespace App\Relationships;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Country;
use App\Models\Department;
use App\Models\Expense;
use App\Models\Filter;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\TransactionsContainer;
use App\Models\User;

trait OrganizationRelationships
{

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
