<?php


namespace App\DatabaseHelpers\FormattedQuery;


trait ItemFormattedQuery
{
    public static function higherSalesItemsQuery($take = 5)
    {
        return self::where([
            ['is_service', false],
            ['is_kit', false]
        ])
            ->select('items.*')
            ->leftJoin('item_statistics', 'item_statistics.item_id', '=', 'items.id')
            ->orderBy('item_statistics.sales_count', 'desc')
            ->take($take)
            ->get();
    }

    public static function latestItemsQuery($take = 5)
    {
        return self::where([
            ['is_service', false],
            ['is_kit', false]
        ])->latest()->take($take)->get();
    }


    public static function bannerItemQuery($take = 5)
    {
        return self::where([
            ['is_service', false],
            ['is_kit', false]
        ])->first();
    }

    public static function formattedCollectionQuery($cell, $orderType = 'desc', $take = 5)
    {
        return self::where([
            ['is_service', false],
            ['is_kit', false]
        ])->orderBy($cell, $orderType)->take($take)->get();
    }

    public static function formattedPackageCollectionQuery($cell = 'id', $orderType = 'desc', $take = 5)
    {
        return self::where([
            ['is_service', false],
            ['is_kit', true]
        ])->orderBy($cell, $orderType)->take($take)->get();
    }

}
