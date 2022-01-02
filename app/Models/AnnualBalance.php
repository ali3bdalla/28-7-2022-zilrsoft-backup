<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class AnnualBalance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
        'balance' => 'float',
    ];
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if(Auth::user() && Auth::user()->active_year)
        {
            static::addGlobalScope('annual', function ( $builder) {
                $builder->where('year', Auth::user()->active_year);
            });
        }
    }

    public function account(): MorphTo
    {
        return $this->morphTo("account");
    }

}
