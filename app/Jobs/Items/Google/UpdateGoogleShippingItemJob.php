<?php

namespace App\Jobs\Items\Google;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MOIREI\GoogleMerchantApi\Facades\ProductApi;

class UpdateGoogleShippingItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Item
     */
    private $item;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Item $item)
    {
        //
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /*
                'kind',
                'channel', 'contentLanguage', 'offerId', 'targetCountry',
                'title', 'description', 'link', 'imageLink', 'additionalImageLinks',
                'adsGrouping', 'adsLabels', 'adsRedirect', 'adult', 'ageGroup',
                'availability', 'availabilityDate', 'brand', 'color', 'condition', 'costOfGoodsSold',
                'gender', 'googleProductCategory', 'gtin', 'itemGroupId', 'mpn',
                'price', 'salePrice', 'salePriceEffectiveDate', 'sellOnGoogleQuantity', 'shipping', 'sizes', 'customAttributes',
                'customLabel0', 'customLabel1', 'customLabel2', 'customLabel3', 'customLabel4',
                'displayAdsId', 'displayAdsLink', 'displayAdsSimilarIds', 'displayAdsTitle', 'displayAdsValue',
                'energyEfficiencyClass', 'excludedDestinations', 'expirationDate',
                'identifierExists', 'includedDestinations', 'installment', 'isBundle',
                'loyaltyPoints', 'material', 'maxEnergyEfficiencyClass', 'maxHandlingTime', 'minEnergyEfficiencyClass', 'minHandlingTime',
                'mobileLink', 'multipack', 'pattern', 'productTypes', 'productHighlights',
                'shippingHeight', 'shippingLabel', 'shippingLength', 'shippingWeight',
                'sizeSystem', 'sizeType', 'taxCategory', 'taxes', 'transitTimeLabel', 'unitPricingBaseMeasure', 'unitPricingMeasure',*/
        if ($this->item->shouldBeSearchable() && $this->item->available_qty) {
            ProductApi::insert(function ($product) {
                $filters = $this->item->filters()->with('value', 'filter')->get();
                $attributes = [];
                foreach ($filters as $filter) {
                    if ($filter->filter && $filter->value)
                        $attributes[$filter->filter->locale_name] = $filter->value->locale_name;
                }
                $link = 'https://msbrshop.com/web/items/' . $this->item->id;
                return $product
                    ->title($this->item->locale_name)
                    ->offerId($this->item->barcode)
                    ->description($this->item->locale_description)
                    ->price(moneyFormatter($this->item->price_with_tax))
                    ->salePrice(moneyFormatter($this->item->online_offer_price))
                    ->imageLink($this->getImageUrl($this->item->item_image_url))
                    ->itemGroupId($this->item->category_id)
                    ->shippingLabel($this->getShippingLabel())
                    ->shippingWeight($this->item->weight)
                    ->additionalImageLinks($this->item->attachments()->pluck('actual_path')->map(function ($path) {
                        return $this->getImageUrl($path);
                    }))
                    ->customAttributes($attributes)
                    ->link($link)
                    ->availability($this->item->available_qty >= 0 ? 'in stock' : 'out of stock')
//            "in stock", "out of stock", or "preorder"
                    ->mobileLink($link);
            })->then(function ($response) {
                echo 'Product inserted';
            });

        } else {

            ProductApi::delete(function ($product) {
                $product
                    ->offerId($this->item->barcode);
            })->then(function ($response) {
            })->catch(function ($erro) {
            });
        }


    }

    public function getImageUrl($path)
    {
        return "https://images.zilrsoft.com/api/enrypt/fit/1250/1670/sm/0/plain/local:///com.zilrsoft//storage/app/public/" . $path;
    }

    private function getShippingLabel()
    {
        $amount = 35 - (float)$this->item->shipping_discount;
        if (!$amount) $amount = 0;


        return "shipping_items_group_" . round($amount);
    }
}

