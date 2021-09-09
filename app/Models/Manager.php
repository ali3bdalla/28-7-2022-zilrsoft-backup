<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


/**
 * @property mixed organization_id
 * @property mixed id
 * @property mixed accounts_closed_at
 * @property mixed created_at
 */
class Manager extends BaseAuthModel
{

    use SoftDeletes;
    use Notifiable;
    use HasFactory;
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

        return $this->name_ar;
    }

    /**
     * @param $option
     * @return int
     */
    public function canDo($option): int
    {
        return $this->can($option) == true ? 1 : 0;

    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        // return 1;
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function branch(): BelongsTo
    {
        // return 1;
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'creator_id');
    }

    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class, 'creator_id');
    }

    public function filters_values(): HasMany
    {
        return $this->hasMany(FilterValues::class, 'creator_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'creator_id');
    }

    public function gateways(): BelongsToMany
    {
        return
            $this->belongsToMany(
                Account::class,
                ManagerGateways::class,
                'manager_id',
                'gateway_id'
            )
                ->withPivot('order_number as order_number')
                ->orderBy('order_number');
    }



    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'creator_id');
    }



    /**
     * @return mixed
     */
    public function resellerClosingAccounts()
    {
        return $this->hasMany(ResellerClosingAccount::class, 'creator_id');
    }
}
