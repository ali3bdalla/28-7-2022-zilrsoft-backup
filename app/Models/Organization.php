<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed clients_chart_account_id
 * @property mixed accounts_organization_id
 * @property mixed accounts_creator_id
 * @property mixed description_ar
 * @property mixed description
 * @property mixed title_ar
 * @property mixed title
 */
class Organization extends BaseModel
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function getLocaleDescriptionAttribute()
    {
        if (app()->isLocale('ar'))
            return $this->description_ar;

        return $this->description;
    }

    public function getLocaleTitleAttribute()
    {
        if (app()->isLocale('ar'))
            return $this->title_ar;

        return $this->title;
    }

    public function getOrganizationTaxAttribute(): float
    {
        return 1.15;
    }


    public function getOrganizationVatAttribute(): int
    {
        return 15;
    }

    public function getLogoAttribute($value): string
    {
        return 'https://images.zilrsoft.com/api/insecure/fit/200/200/no/0/plain/local:///com.zilrsoft/storage/app/public/' . $value;
    }

    public function getStampAttribute($value): string
    {
        return 'https://images.zilrsoft.com/api/insecure/fit/200/200/no/0/plain/local:///com.zilrsoft/storage/app/public/' . $value;
    }

    public function getLocalizedLogoAttribute(): string
    {
        if (app()->isLocale('ar'))
            return "https://zilrsoft.com/images/logo_ar.png";

        return "https://zilrsoft.com/images/logo_en.png";

    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'organization_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'organization_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'organization_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'organization_id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'organization_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'organization_id');
    }

    public function kits(): HasMany
    {
        return $this->hasMany(Item::class, 'organization_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'organization_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'organization_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class, 'organization_id');
    }

    public function managers(): HasMany
    {
        return $this->hasMany(Manager::class, 'organization_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'organization_id');
    }

    public function transactions_containers(): HasMany
    {
        return $this->hasMany(TransactionsContainer::class, 'organization_id');
    }

}
