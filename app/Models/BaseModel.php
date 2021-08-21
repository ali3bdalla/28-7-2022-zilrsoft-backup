<?php

namespace App\Models;

use App\Models\Traits\PostgresTimestamp;
use App\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use Translatable;
    use PostgresTimestamp;

    private static $customTablesOrder = [
        'accounts' => [
            'key' => 'serial',
            'dir' => 'desc',
        ],
        'users' => [
            'key' => 'id',
            'dir' => 'asc',
        ],
        'categories' => [
            'key' => 'sorting',
            'dir' => 'asc',
        ],
    ];

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }

        return $this->name;
    }

    public function getLocaleDescriptionAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_description;
        }

        return $this->description;
    }

    protected static function boot()
    {
        parent::boot();

        if (!app()->environment('testing')) {
            $table = (new static())->getTable();

            if (auth()->guard('manager')->check() || auth()->user()) {
                if (Schema::hasColumn($table, 'organization_id')) {
                    static::addGlobalScope(
                        'organization',
                        function (Builder $builder) use ($table) {
                            $builder->where("{$table}.organization_id", auth()->user()->organization_id);
                        }
                    );
                }
            } else {
                if (Schema::hasColumn($table, 'organization_id')) {
                    static::addGlobalScope(
                        'organization',
                        function (Builder $builder) use ($table) {
                            $builder->where("{$table}.organization_id", 1);
                        }
                    );
                }
            }

            if (Schema::hasColumn($table, 'is_draft')) {
                static::addGlobalScope(
                    'draft',
                    function (Builder $builder) use ($table) {
                        $builder->where("{$table}.is_draft", false);
                    }
                );
            }

            if (auth()->check() && Schema::hasColumn($table, 'is_pending')) {
                static::addGlobalScope(
                    'pending',
                    function (Builder $builder) use ($table) {
                        $builder->where("{$table}.is_pending", false);
                    }
                );
            }

            foreach (self::$customTablesOrder as $key => $order) {
                if ($key == $table) {
                    static::addGlobalScope(
                        'order',
                        function (Builder $builder) use ($order, $table) {
                            $builder->orderBy("{$table}.{$order['key']}", $order['dir']);
                        }
                    );
                }
            }

            if (auth('manager')->check() && !auth('manager')->user()->can('manage branches')) {
                if ('invoices' == $table) {
                    if (Schema::hasColumn($table, 'creator_id')) {
                        static::addGlobalScope(
                            'manager',
                            function (Builder $builder) use ($table) {
                                $builder->where("{$table}.creator_id", auth()->user()->id);
                            }
                        );
                    }
                }

                if ('orders' == $table) {
                    static::addGlobalScope(
                        'manager',
                        function (Builder $builder) use ($table) {
                            $builder->where("{$table}.managed_by_id", auth()->user()->id)->orWhere('status', 'paid');
                        }
                    );
                }
            }

            if (strpos(url()->current(), 'web')) {
                if ('items' == $table) {
                    static::addGlobalScope(
                        'online',
                        function (Builder $builder) use ($table) {
                            $builder->where(
                                [
                                    ["{$table}.is_available_online", true],
                                    ["{$table}.is_kit", false],
                                    ["{$table}.available_qty", '>', 0],
                                ]
                            )
                                ->with('category', 'attachments')->whereHas('category')->whereHas('attachments')->orderBy('available_qty', 'desc');

                            // ->hasModelNumber()
                        }
                    );
                }

                if ('categories' == $table) {
                    static::addGlobalScope(
                        'online',
                        function (Builder $builder) use ($table) {
                            $builder->where(
                                [
                                    ["{$table}.is_available_online", true],
                                ]
                            );
                        }
                    );
                }
            }
        }
    }
}
