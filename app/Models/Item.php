<?php

namespace App\Models;

use App\Events\Item\ItemCreatedEvent;
use App\Scopes\StoreItemScope;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * @property mixed is_fixed_price
 * @property mixed price
 * @property mixed is_expense
 * @property mixed cost
 * @property mixed id
 * @property mixed vts
 * @property mixed available_qty
 * @property mixed data
 * @property mixed items
 * @property mixed vtp
 * @property mixed expense_vendor_id
 * @property mixed invoice_id
 * @property mixed total_credit_amount
 * @property mixed total_debit_amount
 * @property mixed returned_qty
 * @property mixed is_need_serial
 * @property mixed total_cost_amount
 * @property mixed total_stock_amount
 * @property mixed is_service
 * @property mixed ar_name
 * @property mixed creator_id
 * @property mixed online_offer_price
 * @property mixed weight
 * @property mixed category
 * @property mixed en_slug
 * @property HasMany tags
 * @method static findOrFail($id)
 * @method static InRandomOrder()
 * @method static find($input)
 * @method static where(string $string, false $false)
 */
class Item extends BaseModel
{

    use SoftDeletes, Searchable;

    protected $dispatchesEvents = [
        'created' => ItemCreatedEvent::class
    ];
    protected $touches = ['category', 'filters', 'tags', 'attachments'];
    protected $appends = [
        'slug',
        'locale_name',
        'locale_description',
        'item_image_url'
    ];
    protected $casts = [
        'id' => 'integer',
        'is_has_vts' => 'boolean',
        'is_has_vtp' => 'boolean',
        'is_fixed_price' => 'boolean',
        'is_kit' => 'boolean',
        'is_service' => 'boolean',
        'is_need_serial' => 'boolean',
        'available_qty' => 'integer',
        'price' => MoneyValueObject::class,
        'price_with_tax' => MoneyValueObject::class,
        'is_expense' => 'boolean',
        'shipping_discount' => MoneyValueObject::class,
        'online_price' => MoneyValueObject::class,
        'online_offer_price' => MoneyValueObject::class,
    ];
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreItemScope());
    }

    public function warrantySubscription(): BelongsTo
    {
        return $this->belongsTo(WarrantySubscription::class, 'warranty_subscription_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_qty', '>', 0);
    }

    public function getShippingDiscountAttribute($value): float
    {
        return round($value);
    }

    public function getSlugAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->getOriginal("ar_slug") : $this->getOriginal("en_slug");
    }

    public function getAvailableQtyAttribute($value): int
    {
        if ($this->getOriginal("is_kit")) return 2;
        return $value;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getItemImageUrlAttribute()
    {

        $main = $this->attachments()->where('is_main', true)->first();
        if ($main) return $main->url;


        $images = $this->attachments()->get()->toArray();

        if ($images && count($images) >= 1) return $images[0]['url'];


        return "https://zilrsoft-cdn.fra1.digitaloceanspaces.com/images/no_image.png";
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopeKits($query)
    {
        return $query->where('is_kit', true);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ItemTag::class);
    }

    public function scopeIncludingModelNumber($query)
    {
        return $query;
    }

    public function scopeHasModelNumber($query)
    {
        return $query->whereHas('filters', function ($query) {
            $query->where('filter_id', 38);
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ItemSerials::class, 'item_id');
    }


    public function pipeline(): HasMany
    {
        return $this->hasMany(InvoiceItems::class, 'item_id')->orderBy('created_at', 'asc');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }


    public function addKitChildren($items)
    {
        foreach ($items as $kit_item) {
            $obj = collect($kit_item)->only(['organization_id', 'qty', 'discount', 'tax', 'price', 'net', 'total', 'subtotal']);
            $obj['item_id'] = $kit_item['id'];
            $obj['creator_id'] = $this->creator_id;
            $items[] = $this->items()->create(collect($obj)->toArray());
        }
    }

    public function items(): HasMany
    {
        return $this->hasMany(KitItems::class, 'kit_id')->with('item');
    }

    public function fillKitData($data)
    {

        $kit_data = [
            'total' => $data['total'],
            'subtotal' => $data['subtotal'],
            'tax' => $data['tax'],
            'discount' => $data['discount'],
            'net' => $data['net']
        ];

        $this->data()->create($kit_data);
    }

    public function data(): HasOne
    {
        return $this->hasOne(KitData::class, 'kit_id');
    }

    public function fetchKitData($qty, $kit_data): array
    {


        $data['total'] = $kit_data['total'];
        $data['subtotal'] = $kit_data['subtotal'];
        $data['tax'] = $kit_data['tax'];
        $data['net'] = $kit_data['net'];
        $data['price'] = $kit_data['price'];


        return $data;
    }

    public function shouldBeSearchable(): bool
    {
        return ($this->getOriginal("is_category_available_online") and
            $this->getOriginal("available_qty") > 0 and
            $this->getOriginal("organization_id") == 1 and
            $this->getOriginal("is_available_online") and
            $this->attachments()->count() >= 4);
    }

    public function searchableAs(): string
    {
        return 'items_index';
    }

    public function toSearchableArray(): array
    {
        $this->load('tags', 'filters.filter');
        $array = $this->toArray();
        $modelFilter = $this->filters()->where('filter_id', 38)->first();
        if ($modelFilter && $modelFilter->value) {
            $modelName = $modelFilter->value->name;
        } else {
            $modelName = "";
        }
        $array['model_number'] = $modelName;
        $array['tags'] = $this->tags->map(function ($data) {
            return $data['tag'];
        })->toArray();
        foreach ($this->filters()->get() as $filter) {
            if ($filter->value) {
                $array['filters_' . $filter->filter->name][] = $filter->value->name;
                $array['ar_filters_' . $filter->filter->ar_name][] = $filter->value->ar_name;
            }
        }
        $array['category_name'] = $this->category ? $this->category->description : "";
        $array['category_id'] = $this->getOriginal("category_id");
        $array['category_ar_name'] = $this->category ? $this->category->ar_description : "";
        $array['ar_slug'] = $this->getOriginal("ar_slug");
        $array['en_slug'] = $this->getOriginal("en_slug");

        return $array;
    }

    public function filters(): HasMany
    {
        return $this->hasMany(ItemFilters::class, 'item_id');
    }

    public function isAvailableQuantityCanHandle(float $quantity): bool
    {
        if (!$this->isQuantitiable()) return true;
        return $this->available_qty >= $quantity;
    }

    public function isQuantitiable(): bool
    {
        return !$this->is_service && !$this->is_expense && !$this->is_kit;
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('tags', 'serials');
    }
}
