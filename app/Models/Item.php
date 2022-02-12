<?php

namespace App\Models;

use App\Events\Item\ItemCreatedEvent;
use App\Scopes\StoreItemScope;
use App\Traits\OrganizationTarget;
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
 * @property HasMany tags
 * @property mixed is_kit
 * @property mixed locale_name
 * @method static findOrFail($id)
 * @method static InRandomOrder()
 * @method static find($input)
 * @method static where(string $string, false $false)
 */
class Item extends BaseModel
{

    use SoftDeletes, Searchable;
    use OrganizationTarget;

    protected $dispatchesEvents = [
        'created' => ItemCreatedEvent::class
    ];
    protected $touches = ['category', 'filters', 'tags', 'attachments'];

    protected $appends = [
        'locale_name',
        'locale_description',
        "view_url",
        'photo'
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

    public function setIsPublishedAttribute()
    {
        $this->attributes['is_published'] = $this->shouldBeSearchable();
    }

    public function shouldBeSearchable(): bool
    {
        return
            $this->getOriginal("is_category_available_online") == true
            && $this->getOriginal("available_qty") > 0
            && $this->getOriginal("organization_id") == 1
            && $this->getOriginal("is_available_online") == true
            && $this->attachments()->count() > 3;
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function warrantySubscription(): BelongsTo
    {
        return $this->belongsTo(WarrantySubscription::class, 'warranty_subscription_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_qty', '>', 0);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }


    public function scopeKits($query)
    {
        return $query->where('is_kit', true);
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
        return $this->hasMany(InvoiceItems::class, 'item_id')->orderBy('created_at');
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

    public function getViewUrlAttribute(): string
    {
        return route('web.items.show', $this->slug);
    }

    public function getPhotoAttribute(): string
    {
        $attachment = $this->attachments()->whereIsMain(true)->first();
        if (!$attachment instanceof Attachment) {
            $attachment = $this->attachments()->first();
        }
        return $attachment ? $attachment->actual_path : "";
    }

    public function toSearchableArray(): array
    {
        return array_merge([
            "view_url" => $this->view_url,
            'id' => $this->id,
            'online_offer_price' => $this->online_offer_price,
            'barcode' => $this->barcode,
            'name' => $this->getOriginal('name'),
            'ar_name' => $this->getOriginal("ar_name"),
            'tags' => $this->tags()->pluck('tag')->toArray(),
            'url' => $this->view_url,
            "category_id" => $this->category_id,
            'photo' => $this->photo,
            'model_number' => $this->modelNumber()
        ], $this->searhCategoryDetails(), $this->searchFilters());


    }

    public function tags(): HasMany
    {
        return $this->hasMany(ItemTag::class);
    }

    public function modelNumber()
    {
        $modelFilter = $this->filters()->with("value")->where('filter_id', 38)->first();
        if ($modelFilter && $modelFilter->value) {
            return $modelFilter->value->name;
        }
        return "";
    }

    public function filters(): HasMany
    {
        return $this->hasMany(ItemFilters::class, 'item_id');
    }

    public function searhCategoryDetails(): array
    {
        if ($this->category)
            return [
                "category_name" => $this->category->description,
                "category_ar_name" => $this->category->ar_description];
        return [];
    }

    public function searchFilters(): array
    {

        $filters = [];
        foreach ($this->filters()->whereHas("value")->whereHas("filter")->with("value", "filter")->get() as $itemFilter) {
            $filters['filters_' . $itemFilter->filter->name][] = $itemFilter->value->name;
            $filters['ar_filters_' . $itemFilter->filter->ar_name][] = $itemFilter->value->ar_name;
        }

        return $filters;

    }

    public function isAvailableQuantityCanHandle(float $quantity): bool
    {
        if (!$this->isQuantifiable()) return true;
        return $this->available_qty >= $quantity;
    }

    public function isQuantifiable(): bool
    {
        return !$this->is_service && !$this->is_expense && !$this->is_kit;
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('tags', 'serials');
    }
}
