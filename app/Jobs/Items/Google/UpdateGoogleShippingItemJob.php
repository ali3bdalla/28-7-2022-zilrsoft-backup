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
        if ($this->item->shouldBeSearchable() && $this->item->available_qty) {
            ProductApi::insert(function ($product) {
                $link = 'https://msbrshop.com/web/items/' . $this->item->ar_slug;
                return $product
                    ->title($this->item->locale_name)
                    ->offerId($this->item->barcode)
                    ->description($this->item->locale_description)
                    ->price(moneyFormatter($this->item->price_with_tax))
                    ->salePrice(moneyFormatter($this->item->online_offer_price))
                    ->imageLink($this->getImageUrl($this->item->photo))
                    ->itemGroupId($this->item->category_id)
                    ->shippingLabel($this->getShippingLabel())
                    ->shippingWeight($this->item->weight)
                    ->condition("new")
                    ->additionalImageLinks($this->item->attachments()->pluck('actual_path')->map(function ($path) {
                        return $this->getImageUrl($path);
                    }))
                    ->link($link)
                    ->contentLanguage('ar')
                    ->availability($this->item->available_qty >= 0 ? 'in stock' : 'out of stock');
            })->then(function ($response) {
                echo 'Product inserted';
            })->catch(function ($erro) {
                var_dump($erro);
            });


            ProductApi::insert(function ($product) {
                $filters = $this->item->filters()->with('value', 'filter')->get();
                $attributes = [];
                foreach ($filters as $filter) {
                    if ($filter->filter && $filter->value)
                        $attributes[$filter->filter->name] = $filter->value->name;
                }
                $link = 'https://msbrshop.com/web/items/' . $this->item->id;
                return $product
                    ->title($this->item->name)
                    ->offerId($this->item->barcode)
                    ->description($this->item->description)
                    ->price(moneyFormatter($this->item->price_with_tax))
                    ->salePrice(moneyFormatter($this->item->online_offer_price))
                    ->imageLink($this->getImageUrl($this->item->photo))
                    ->itemGroupId($this->item->category_id)
                    ->shippingLabel($this->getShippingLabel())
                    ->shippingWeight($this->item->weight)
                    ->condition("new")
                    ->additionalImageLinks($this->item->attachments()->pluck('actual_path')->map(function ($path) {
                        return $this->getImageUrl($path);
                    }))
//                    ->customAttributes($attributes)
                    ->link($link)
                    ->contentLanguage('en')
                    ->availability($this->item->available_qty >= 0 ? 'in stock' : 'out of stock');
//            "in stock", "out of stock", or "preorder"
//                    ->mobileLink($link);
            })->then(function ($response) {
                echo 'Product inserted';
            })->catch(function ($erro) {
                var_dump($erro);
            });


        } else {

            ProductApi::delete(function ($product) {
                $product
                    ->contentLanguage('ar')
                    ->offerId($this->item->barcode);
            })->then(function ($response) {
            })->catch(function ($erro) {
            });

            ProductApi::delete(function ($product) {
                $product
                    ->contentLanguage('en')
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
        if ($amount < 0) $amount = 0;

        return "shipping_items_group_" . round($amount);
    }

    private function getShippingAmount()
    {
        $amount = 35 - (float)$this->item->shipping_discount;
        if ($amount) return $amount;
        return 0;
    }
}

