<?php

namespace App\Providers;

use App\Models\InvoiceItems;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\KitItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {


        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerFinialValidations();

        Validator::extendImplicit(
            'phone',
            function ($attribute, $value, $parameters) {
                return true;
            }
        );
        Validator::extendImplicit(
            'price',
            function ($attribute, $value, $args) {

                return preg_match('/^\d{0,8}(\.\d{1,8})?$/', $value);
            }
        );

        Validator::extendImplicit(
            'organization_exists',
            function ($attribute, $value, $parameters, $validator) {
                $model = app($parameters[0]);


                if (!$model instanceof Model)
                    return false;


                $table = (new $model)->getTable();


                if (!Schema::hasColumn($table, $parameters[1]))
                    return false;


                return $model::where($parameters[1], $value)->count() > 0;
            }
        );

        Validator::extendImplicit(
            'organization_unique',
            function ($attribute, $value, $parameters, $validator) {
                $model = app($parameters[0]);
                if (!$model instanceof Model)
                    return false;

                $table = (new $model)->getTable();

                if (!Schema::hasColumn($table, $parameters[1]))
                    return false;

                return $model::where($parameters[1], $value)->count() === 0;
            }
        );

    }


    public function registerFinialValidations()
    {

        Validator::extendImplicit(
            'salesItemQty',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $id = request()->input("{$first}.{$index}.id");
                $item = Item::findOrFail($id);
                if ($item->is_kit || $item->is_service || $item->is_expense) {
                    return true;
                }

                return $item->available_qty >= (float)$value;
            }
        );


        Validator::extendImplicit(
            'purchaseItemPrice',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $end = $str_attr[2];
                $id = request()->input("{$first}.{$index}.id");
                $item = Item::findOrFail($id);
                if ($item->is_expense) {
                    return $value !== null && is_numeric($value);
                } else {
                    return true;
                }
            }
        );


        Validator::extendImplicit(
            'price',
            function ($attribute, $value, $args) {

                return preg_match('/^\d{0,8}(\.\d{1,8})?$/', $value);
            }
        );

        //
        Validator::extendImplicit(
            'itemVendorExpenseId',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $id = request()->input("{$first}.{$index}.id");
                $item = Item::findOrFail($id);
                if ($item->is_expense) {
                    return $item->expense_vendor_id > 0;
                }
                return true;
            }
        );


        Validator::extendImplicit(
            'salesExpensesPurchasePrice',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $end = $str_attr[2];
                $id = request()->input("{$first}.{$index}.id");
                $item = Item::findOrFail($id);
                if ($item->is_expense) {
                    return is_numeric($value) && $value >= 0;
                }
                return true;
            }
        );

        Validator::extendImplicit(
            'newInvoiceItemSerials',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $end = $str_attr[2];

                $id = request()->input("{$first}.{$index}.id");
                $qty = request()->input("{$first}.{$index}.qty");
                $item = Item::findOrFail($id);
                if ($item->is_need_serial) {
                    if (!is_array($value) || count($value) != (float)$qty) {
                        return false;
                    }
                }
                return true;
            }
        );


        Validator::extendImplicit(
            'returnInvoiceItemSerials',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $end = $str_attr[2];
                $id = request()->input("{$first}.{$index}.id");
                $qty = request()->input("{$first}.{$index}.returned_qty");
                $invoiceItem = InvoiceItems::findOrFail($id);
                if ($invoiceItem->item->is_need_serial) {
                    if (!is_array($value) || count($value) != (float)$qty) {
                        return false;
                    }
                }
                return true;
            }
        );


        Validator::extendImplicit(
            'priceAndDiscount',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $index = $str_attr[1];
                $first = $str_attr[0];
                $end = $str_attr[2];
                $id = request()->input("{$first}.{$index}.id");
                $item = Item::findOrFail($id);

                if ($end == "price")
                    return $item->is_kit ? true : is_numeric($value);
                else {
                    return !$item->is_kit && !$item->is_expense ? is_numeric($value) : true;
                }
            }
        );


        Validator::extendImplicit(
            'mobileNumber',
            function ($attribute, $value, $args) {
                return preg_match("/(5)[0-9]{8}/", $value);
            },
            app()->getLocale() === 'ar' ? '?????? ???????????? ???????????? ?????? ????????' : 'invalid mobile number'
        );


        Validator::extendImplicit(
            'salesKitItemValidator',
            function ($attribute, $value, $args) {
                $str_attr = explode('.', $attribute);
                $kitIndex = $str_attr[1];
                $itemInKitIndex = $str_attr[3];


                $kitItemId = request()->input("items.{$kitIndex}.items.{$itemInKitIndex}.id");
                $kitId = request()->input("items.{$kitIndex}.id");
                $kitQty = request()->input("items.{$kitIndex}.qty");
                $dbKitItem = KitItems::where(
                    [
                        ['kit_id', $kitId],
                        ['item_id', $kitItemId]
                    ]
                )->with('item')->firstOrFail();

                $requestedQty = (float)$kitQty * $dbKitItem->qty;
                if ($requestedQty > $dbKitItem->item->available_qty) {
                    return false;
                }
                if ($dbKitItem->item->is_need_serial) {
                    $serials = request()->input("items.{$kitIndex}.items.{$itemInKitIndex}.serials");
                    if ($serials == null) {
                        return false;
                    }


                    if (count($serials) != $requestedQty) {
                        return false;
                    }


                    foreach ($serials as $serial) {
                        $itemSerial = ItemSerials::where(
                            [
                                ['serial', $serial],
                                ['item_id', $dbKitItem->item_id],
                            ]
                        )->whereIn('status', ['in_stock', 'return_sale'])->first();
                        if ($itemSerial == null) {
                            return false;
                        }
                    }
                }


                return true;
            }
        );


        //

    }
}
