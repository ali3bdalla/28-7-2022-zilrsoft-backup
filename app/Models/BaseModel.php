<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{

    private static $customTablesOrder = ['accounts' => 'serial'];

    protected static function boot()
    {
        parent::boot();
        $table = (new static )->getTable();

        if (auth()->guard('manager')->check() || auth()->user()) {
            if (Schema::hasColumn($table, 'organization_id')) {
                static::addGlobalScope('organization', function (Builder $builder) use ($table) {
                    $builder->where("{$table}.organization_id", auth()->user()->organization_id);
                });
            }

        }
        if (Schema::hasColumn($table, 'is_draft')) {
            static::addGlobalScope('draft', function (Builder $builder) use ($table) {
                $builder->where("{$table}.is_draft", false);
            });
        }

        if (auth()->check() && Schema::hasColumn($table, 'pending')) {
            static::addGlobalScope('pending', function (Builder $builder) use ($table) {
                $builder->where("{$table}.is_pending", false);
            });
        }

        if (!key_exists($table, self::$customTablesOrder)) {
            static::addGlobalScope('order', function (Builder $builder) use ($table) {
                $builder->orderBy("{$table}.created_at", 'desc');
            });
        }

        foreach (self::$customTablesOrder as $key => $order) {
            if ($key == $table) {
                static::addGlobalScope('order', function (Builder $builder) use ($order, $table) {
                    $builder->orderBy("{$table}.{$order}", 'desc');
                });
            }
        }

        if (auth()->check() && !auth()->user()->can('manage branches') && $table == 'invoices') {
            if (Schema::hasColumn($table, 'creator_id')) {
                static::addGlobalScope('manager', function (Builder $builder) use ($table) {
                    $builder->where("{$table}.creator_id", auth()->user()->id);
                });
            }
            // static::addGlobalScope('manager', function (Builder $builder) {
            //     $builder->where('creator_id', auth()->user()->id);
            // });
        }

        if ($table == 'items' && !auth()->check()) {
            static::addGlobalScope('online', function (Builder $builder) use ($table) {
                $builder->where([
                    ["{$table}.is_kit", false],
                    ["{$table}.available_qty", '>',0]
                ]);
            });

        }

    }
}
