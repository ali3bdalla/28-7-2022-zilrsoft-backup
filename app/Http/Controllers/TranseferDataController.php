<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Filter;
use App\FilterValues;
use App\Category;
use App\ItemFilters;
use App\Item;
use App\User;
use App\Manager;
use App\CategoryFilters;
class TranseferDataController
{
   
    public function index()
    {

        // $this->filters();
        // $this->filters_values();
        // $this->categories();
        // $this->categroies_filters();
        // $this->items();
        $this->items_filters();

        // return ;
    }





    public function  filters()
    {
        DB::connection('old')->table('Tb_Filters')->orderBy('id')->each(function($old,$index){
            $object = new Filter();
            $object->organization_id = $old->organization_id;
            $object->creator_id = $old->created_by;
            $object->name = $old->name;
            $object->ar_name = $old->ar_name;
            $object->save();
        });
    }



    public function  filters_values()
    {
        DB::connection('old')->table('Tb_Filter_values')->orderBy('id')->each(function($old,$index){
            $object = new FilterValues();
            $object->id = $old->id;
            $object->organization_id = $old->organization_id;
            $object->filter_id = $old->filter_id;
            $object->creator_id = $old->created_by;
            $object->name = $old->name;
            $object->ar_name = $old->ar_name;
            $object->save();
        });
    }



    public function  categories()
    {
        DB::connection('old')->table('Tb_Categories')->orderBy('id')->each(function($old,$index){
            $object = new Category();
            $object->id = $old->id;
            $object->organization_id = $old->organization_id;
            $object->parent_id = $old->parent_id;
            $object->creator_id = $old->created_by;
            $object->name = $old->name;
            $object->ar_name = $old->ar_name;

            $object->description = $old->description;
            $object->ar_description = $old->ar_description;
            $object->status = $old->status;

            $object->save();
        });
    }



    public function  categroies_filters()
    {
        DB::connection('old')->table('Tb_Category_filters')->orderBy('id')->each(function($old,$index){
            $object = new CategoryFilters();
            $object->id = $old->id;
            $object->organization_id = $old->organization_id;
            $object->filter_id = $old->filter_id;
            $object->creator_id = $old->created_by;
            $object->category_id = $old->category_id;
            $object->sorting = $old->sorting;

            $object->save();
        });
    }





    public function  items()
    {
        DB::connection('old')->table('Tb_Products')->orderBy('id')->each(function($old,$index){
            $object = new Item();
            $object->id = $old->id;
            $object->organization_id = $old->organization_id;
            $object->creator_id = $old->created_by;
            $object->category_id = $old->category_id;
            $object->name = $old->name;
            $object->ar_name = $old->ar_name;
            $object->barcode = $old->barcode;
            $object->is_kit = $old->isKit;
            $object->is_fixed_price = $old->fixed_price;
            $object->is_has_vts = $old->has_sale_vat == 'yes' ? true : false ;
            $object->is_has_vtp = $old->has_purchase_vat == 'yes' ? true : false ;

            if($old->serial_number=="required"){
                $serial_number = true;
            }else
            {
                $serial_number = false;
            }

            $object->is_need_serial = $serial_number;
            $object->is_service = $old->is_service  ;
            $object->price = $old->price;
            $object->price_with_tax = $old->price_tax;
            $object->last_p_price = $old->last_price;
            $object->cost = $old->cost;
            $object->vts = $old->vat_sale;
            $object->vtp = $old->vat_purchase;
            $object->available_qty = $old->available_qty;
        

            $object->save();
        });
    }




















    public function  items_filters()
    {
        DB::connection('old')->table('Tb_Product_filters')->orderBy('id')->each(function($old,$index){
            $object = new ItemFilters();
            // $object->id = $old->id;
            $object->organization_id = $old->organization_id;
            $object->filter_id = $old->filter_id;
            $object->creator_id = $old->created_by;
            $object->filter_value = $old->filter_value;
            $object->item_id = $old->product_id;
            // $object->sorting = $old->sorting;

            $object->save();
        });
    }





}
