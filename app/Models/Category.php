<?php

namespace App\Models;

use App\Models\Traits\NestingTrait;
use App\Scopes\StoreCategoryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property string locale_name
 * @property string ar_name
 * @property string name
 * @property string image
 * @property string description
 * @property mixed id
 */
class Category extends BaseModel
{
    use  SoftDeletes, NestingTrait;


    protected $appends = [
        'locale_name',
        'label',
        'image_url',
        'products_count'
    ];
    protected $guarded = [];
    protected $casts = [
        'is_available_online' => "int"
    ];

    /**
     * @param Model $model
     * @return array|null
     */
    public static function getAllParentNestedChildren(Model $model): ?array
    {
        $children = [];
        foreach ($model->children()->get() as $child) {
            $child_children = self::getAllParentNestedChildren($child);
            if ($child_children != null) {
                $child['children'] = $child_children;
            }

            $children[] = $child;
        }

        return $children == [] ? null : $children;
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * @param Model $model
     * @return array|null
     */
    public static function infinityChildrenIds(Model $model): ?array
    {
        $children = [];
        foreach ($model->children()->get() as $child) {
            $child_children = self::infinityChildrenIds($child);
            if ($child_children != null) {
                foreach ($child_children as $grand_child) {
                    $children[] = $grand_child;
                }
            }

            $children[] = $child['id'];
        }

        return $children == [] ? null : $children;
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreCategoryScope());
    }

    public function getLocaleNameAttribute()
    {
        if (strpos(url()->current(), 'web')) {
            if (app()->isLocale('ar')) {
                return $this->ar_description;
            }
            return $this->description;
        }
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }
        return $this->name;
    }

    public function getLabelAttribute()
    {


        return $this->locale_name;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Filter::class, 'category_filters', 'category_id', 'filter_id')->orderBy('category_filters.id', 'asc');
    }

    public function filtersValues(): HasMany
    {
        return $this->hasMany(CategoryFilterValues::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'category_id');
    }

    /*web*/

    public function getProductsCountAttribute()
    {

        return Item::whereIn('category_id', $this->getChildrenIncludeMe())->count();
    }


    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image);
    }
}
