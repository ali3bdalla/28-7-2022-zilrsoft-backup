<?php

namespace App\Models;

use Carbon\Carbon;


/**
 * @method static inRandomOrder()
 */
class Manager extends BaseAuthModel
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = [
        'locale_name'
    ];


    public function getLocaleNameAttribute()
    {

        return $this->name;
    }

    /**
     * @param $option
     * @return int
     */
    public function canDo($option)
    {
        return $this->can($option) == true ? 1 : 0;

    }


    /**
     * @return mixed
     */
    public function resellerClosingAccounts()
    {
        return $this->hasMany(ResellerClosingAccount::class, 'creator_id');
    }

//    public function dailyTransactionsAmount($period = null)
//    {
////
//        $startAt = Carbon::parse($period)->toDateTimeString();
//        $dailyAccount = Account::where([
//            ['slug', 'temp_reseller_account'],
//            ['is_system_account', true],
//        ])->first();
//
//        return $dailyAccount->debit_transaction()->where('creator_id', $this->id)
//                ->sum('amount') -
//            $dailyAccount->credit_transaction()->where('creator_id', $this->id)
//                ->sum('amount');
////
//    }
//


    public function organization()
    {
        return $this->belongsTo(Organization::class,
            'organization_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        // return 1;
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function branch()
    {
        // return 1;
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'creator_id');
    }

    public function filters()
    {
        return $this->hasMany(Filter::class, 'creator_id');
    }

    public function filters_values()
    {
        return $this->hasMany(FilterValues::class, 'creator_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'creator_id');
    }

    public function gateways()
    {
//			->withPivot('created_at as relationship_created_at')
        return
            $this->belongsToMany(Account::class,
                'manager_gateways',
                'manager_id',
                'gateway_id')
                ->withPivot('order_number as order_number')
                ->orderBy('order_number', 'asc')
                ->withTimestamps();
    }


}
