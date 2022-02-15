<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Searchable Attributes
    |--------------------------------------------------------------------------
    |
    | Limits the scope of a search to the attributes listed in this setting. Defining
    | specific attributes as searchable is critical for relevance because it gives
    | you direct control over what information the search engine should look at.
    |
    | Supported: Null, Array
    | Example: ["name", "email", "unordered(city)"]
    |
    */

    'searchableAttributes' => [
        'tags',
        'barcode',
        'category_ar_name',
        'category_name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Ranking
    |--------------------------------------------------------------------------
    |
    | Custom Ranking is about leveraging business metrics to effectively rank search
    | results - it's crucial for any successful search experience. Make sure that
    | only "numeric" attributes are used, such as the number of sales or views.
    |
    | Supported: Null, Array
    | Examples: ['desc(comments_count)', 'desc(views_count)']
    |
    */

    'customRanking' => null,

    /*
    |--------------------------------------------------------------------------
    | Remove Stop Words
    |--------------------------------------------------------------------------
    |
    | Stop word removal is useful when you have a query in natural language, e.g.
    | “what is a record?”. In that case, the engine will remove “what”, “is”,
    | before executing the query, and therefore just search for “record”.
    |
    | Supported: Null, Boolean, Array
    |
    */

    'removeStopWords' => null,

    /*
    |--------------------------------------------------------------------------
    | Disable Typo Tolerance
    |--------------------------------------------------------------------------
    |
    | Algolia provides robust "typo-tolerance" out-of-the-box. This parameter accepts an
    | array of attributes for which typo-tolerance should be disabled. This is useful,
    | for example, products that might require SKU search without "typo-tolerance".
    |
    | Supported: Null, Array
    | Example: ['id', 'sku', 'reference', 'code']
    |
    */

    'disableTypoToleranceOnAttributes' => [],

    /*
    |--------------------------------------------------------------------------
    | Attributes For Faceting
    |--------------------------------------------------------------------------
    |
    | Your index comes with no categories. By designating an attribute as a facet, this enables
    | Algolia to compute a set of possible values that can later be used to create categories
    | or filters. You can also get a count of records that match those values.
    |
    | Supported: Null, Array
    | Example: ['type', 'filterOnly(country)', 'searchable(city)',]
    |
    */

    'attributesForFaceting' => [
        'searchable(online_offer_price)',
        'searchable(category_name)',
        'searchable(category_ar_name)',
        'searchable(filters_BRAND)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x85\xd8\xa7\xd8\xb1\xd9\x83\xd8\xa9)",
        'searchable(filters_Model name)',
        "searchable(ar_filters_\xd8\xa7\xd8\xb3\xd9\x85 \xd8\xa7\xd9\x84\xd9\x85\xd9\x88\xd8\xaf\xd9\x8a\xd9\x84)",
        'searchable(filters_COLOR)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x84\xd9\x88\xd9\x86)",
        'searchable(filters_OPERATING SYSTEM)',
        "searchable(ar_filters_\xd9\x86\xd8\xb8\xd8\xa7\xd9\x85 \xd8\xa7\xd9\x84\xd8\xaa\xd8\xb4\xd8\xba\xd9\x8a\xd9\x84)",
        'searchable(filters_Gigabites)',
        "searchable(ar_filters_\xd8\xac\xd9\x8a\xd8\xac\xd8\xa7\xd8\xa8\xd8\xa7\xd9\x8a\xd8\xaa)",
        'searchable(filters_RAM)',
        "searchable(ar_filters_\xd8\xb1\xd8\xa7\xd9\x85)",
        'searchable(filters_RESOLUTION)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd8\xaf\xd9\x82\xd8\xa9)",
        'searchable(filters_SECONDARY CAMERA)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x83\xd8\xa7\xd9\x85\xd9\x8a\xd8\xb1\xd8\xa7 \xd8\xa7\xd9\x84\xd8\xab\xd8\xa7\xd9\x86\xd9\x8a\xd8\xa9)",
        'searchable(filters_SCREEN SIZE)',
        "searchable(ar_filters_\xd8\xad\xd8\xac\xd9\x85 \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa7\xd8\xb4\xd8\xa9)",
        'searchable(filters_CHARGER COMATIBILITY)',
        "searchable(ar_filters_\xd9\x85\xd9\x86\xd9\x81\xd8\xb0 \xd8\xa7\xd9\x84\xd8\xb4\xd8\xad\xd9\x86)",
        'searchable(filters_EXPANDABLE STORAGE)',
        "searchable(ar_filters_\xd9\x88\xd8\xad\xd8\xaf\xd8\xa9 \xd8\xaa\xd8\xae\xd8\xb2\xd9\x8a\xd9\x86 \xd8\xa7\xd8\xb6\xd8\xa7\xd9\x81\xd9\x8a\xd8\xa9)",
        'searchable(filters_DISPLAY TYPE)',
        "searchable(ar_filters_\xd8\xb4\xd8\xa7\xd8\xb4\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xb9\xd8\xb1\xd8\xb6)",
        'searchable(filters_ink TYPE)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xa7\xd9\x84\xd8\xad\xd8\xa8\xd8\xb1)",
        'searchable(filters_software version)',
        "searchable(ar_filters_\xd8\xa7\xd8\xb5\xd8\xaf\xd8\xa7\xd8\xb1 \xd8\xa7\xd9\x84\xd8\xa8\xd8\xb1\xd9\x86\xd8\xa7\xd9\x85\xd8\xac)",
        'searchable(filters_STORAGE TYPE)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd9\x88\xd8\xad\xd8\xaf\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xaa\xd8\xae\xd8\xb2\xd9\x8a\xd9\x86)",
        'searchable(filters_HARD DISK SIZE)',
        "searchable(ar_filters_\xd8\xad\xd8\xac\xd9\x85 \xd9\x88\xd8\xad\xd8\xaf\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xaa\xd8\xae\xd8\xb2\xd9\x8a\xd9\x86)",
        'searchable(filters_PROCESSOR CPU model)',
        "searchable(ar_filters_\xd9\x85\xd9\x88\xd8\xaf\xd9\x8a\xd9\x84 \xd8\xa7\xd9\x84\xd9\x85\xd8\xb9\xd8\xa7\xd9\x84\xd8\xac)",
        'searchable(filters_SCREEN)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa7\xd8\xb4\xd8\xa9)",
        'searchable(filters_GRAPHICS MEMORY)',
        "searchable(ar_filters_\xd9\x83\xd8\xb1\xd8\xaa \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa7\xd8\xb4\xd8\xa9)",
        'searchable(filters_LAPTOP TYPE)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xa7\xd9\x84\xd9\x85\xd8\xad\xd9\x85\xd9\x88\xd9\x84)",
        'searchable(filters_Frequency)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd8\xaa\xd8\xb1\xd8\xaf\xd8\xaf)",
        'searchable(filters_CONNECTION INTERFACE)',
        "searchable(ar_filters_\xd9\x88\xd8\xa7\xd8\xac\xd9\x87\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xa7\xd8\xaa\xd8\xb5\xd8\xa7\xd9\x84)",
        'searchable(filters_Battery Capacity)',
        "searchable(ar_filters_\xd8\xad\xd8\xac\xd9\x85 \xd8\xa7\xd9\x84\xd8\xa8\xd8\xb7\xd8\xa7\xd8\xb1\xd9\x8a\xd8\xa9)",
        'searchable(filters_Number of USB Ports)',
        "searchable(ar_filters_\xd8\xb9\xd8\xaf\xd8\xaf \xd9\x85\xd9\x86\xd8\xa7\xd9\x81\xd8\xb0 \xd8\xa7\xd9\x84\xd9\x8a\xd9\x88 \xd8\xa7\xd8\xb3 \xd8\xa8\xd9\x8a)",
        'searchable(filters_Processor CPU Speed)',
        "searchable(ar_filters_\xd8\xb3\xd8\xb1\xd8\xb9\xd8\xa9 \xd8\xa7\xd9\x84\xd9\x85\xd8\xb9\xd8\xa7\xd9\x84\xd8\xac)",
        'searchable(filters_Headphone Type)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xb3\xd9\x85\xd8\xa7\xd8\xb9\xd8\xa7\xd8\xaa \xd8\xa7\xd9\x84\xd8\xb1\xd8\xa3\xd8\xb3)",
        'searchable(filters_printer type)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xa7\xd9\x84\xd8\xb7\xd8\xa7\xd8\xa8\xd8\xb9\xd8\xa9)",
        'searchable(filters_device Placement)',
        "searchable(ar_filters_\xd9\x85\xd9\x83\xd8\xa7\xd9\x86 \xd8\xa7\xd9\x84\xd9\x88\xd8\xad\xd8\xaf\xd8\xa9)",
        'searchable(filters_usb interface)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xa7\xd9\x84\xd9\x86\xd8\xa7\xd9\x82\xd9\x84)",
        'searchable(filters_Paper Size)',
        "searchable(ar_filters_\xd8\xad\xd8\xac\xd9\x85 \xd8\xa7\xd9\x84\xd9\x88\xd8\xb1\xd9\x82)",
        'searchable(filters_Modem Type)',
        "searchable(ar_filters_\xd9\x86\xd9\x88\xd8\xb9 \xd8\xa7\xd9\x84\xd9\x85\xd9\x88\xd8\xaf\xd9\x85)",
        'searchable(filters_Environment)',
        "searchable(ar_filters_\xd8\xa8\xd9\x8a\xd8\xa6\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xa5\xd8\xb3\xd8\xaa\xd8\xae\xd8\xaf\xd8\xa7\xd9\x85)",
        'searchable(filters_network speed)',
        "searchable(ar_filters_\xd8\xb3\xd8\xb1\xd8\xb9\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa8\xd9\x83\xd8\xa9)",
        'searchable(filters_Number Of LAN Ports)',
        "searchable(ar_filters_\xd8\xb9\xd8\xaf\xd8\xaf \xd9\x85\xd9\x86\xd8\xa7\xd9\x81\xd8\xb0 \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa8\xd9\x83\xd8\xa9)",
        'searchable(filters_weight)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x88\xd8\xb2\xd9\x86)",
        'searchable(filters_Dimensions)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd8\xa3\xd8\xa8\xd8\xb9\xd8\xa7\xd8\xaf)",
        'searchable(filters_Model number)',
        "searchable(ar_filters_\xd8\xb1\xd9\x82\xd9\x85 \xd8\xa7\xd9\x84\xd9\x85\xd9\x88\xd8\xaf\xd9\x8a\xd9\x84)",
        'searchable(filters_Auto/Manual)',
        "searchable(ar_filters_\xd8\xa7\xd9\x88\xd8\xaa\xd9\x88\xd9\x85\xd8\xa7\xd8\xaa\xd9\x8a\xd9\x83\xd9\x8a / \xd9\x8a\xd8\xaf\xd9\x88\xd9\x8a)",
        'searchable(filters_Number Of Channels)',
        "searchable(ar_filters_\xd8\xb9\xd8\xaf\xd8\xaf \xd8\xa7\xd9\x84\xd9\x82\xd9\x86\xd9\x88\xd8\xa7\xd8\xaa)",
        'searchable(filters_length)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd8\xb7\xd9\x88\xd9\x84)",
        'searchable(filters_distance)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x85\xd8\xb3\xd8\xa7\xd9\x81\xd8\xa9)",
        'searchable(filters_vision angle)',
        "searchable(ar_filters_\xd8\xb2\xd8\xa7\xd9\x88\xd9\x8a\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xb1\xd8\xa4\xd9\x8a\xd8\xa9)",
        'searchable(filters_voltage)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x81\xd9\x88\xd9\x84\xd8\xaa)",
        'searchable(filters_amps)',
        "searchable(ar_filters_\xd8\xa3\xd9\x85\xd8\xa8\xd9\x8a\xd8\xb1)",
        'searchable(filters_Antenna Gain)',
        "searchable(ar_filters_\xd9\x82\xd9\x88\xd8\xa9 \xd8\xa7\xd9\x84\xd9\x87\xd9\x88\xd8\xa7\xd8\xa6\xd9\x8a)",
        'searchable(filters_licenses number)',
        "searchable(ar_filters_\xd8\xb9\xd8\xaf\xd8\xaf \xd8\xa7\xd9\x84\xd8\xaa\xd8\xb1\xd8\xa7\xd8\xae\xd9\x8a\xd8\xb5)",
        'searchable(filters_Period)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x81\xd8\xaa\xd8\xb1\xd8\xa9)",
        'searchable(filters_QUANTITY)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x83\xd9\x85\xd9\x8a\xd8\xa9)",
        'searchable(filters_Material)',
        "searchable(ar_filters_\xd9\x85\xd8\xa7\xd8\xaf\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xaa\xd8\xb5\xd9\x86\xd9\x8a\xd8\xb9)",
        'searchable(filters_Orient)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd8\xac\xd9\x87\xd9\x87)",
        'searchable(filters_Series release name)',
        "searchable(ar_filters_\xd8\xa7\xd8\xb3\xd9\x85 \xd8\xb3\xd9\x84\xd8\xb3\xd9\x84\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xa7\xd8\xb5\xd8\xaf\xd8\xa7\xd8\xb1)",
        'searchable(filters_Series release number)',
        "searchable(ar_filters_\xd8\xb1\xd9\x82\xd9\x85 \xd8\xb3\xd9\x84\xd8\xb3\xd9\x84\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xa7\xd8\xb5\xd8\xaf\xd8\xa7\xd8\xb1)",
        'searchable(filters_Number of ports)',
        "searchable(ar_filters_\xd8\xb9\xd8\xaf\xd8\xaf \xd8\xa7\xd9\x84\xd9\x85\xd9\x86\xd8\xa7\xd9\x81\xd8\xb0)",
        'searchable(filters_type)',
        "searchable(ar_filters_\xd8\xa7\xd9\x84\xd9\x86\xd9\x88\xd8\xb9)",
    ],

    /*
    |--------------------------------------------------------------------------
    | Unretrievable Attributes
    |--------------------------------------------------------------------------
    |
    | This is particularly important for security or business reasons, where some attributes are
    | used only for ranking or other technical purposes, but should never be seen by your end
    | users, such as: total_sales, permissions, stock_count, and other private information.
    |
    | Supported: Null, Array
    | Example: ['total_sales', 'permissions', 'stock_count',]
    |
    */

    'unretrievableAttributes' => null,

    /*
    |--------------------------------------------------------------------------
    | Ignore Plurals
    |--------------------------------------------------------------------------
    |
    | Treats singular, plurals, and other forms of declensions as matching terms. When
    | enabled, will make the engine consider “car” and “cars”, or “foot” and “feet”,
    | equivalent. This is used in conjunction with the "queryLanguages" setting.
    |
    | Supported: Null, Boolean, Array
    |
    */

    'ignorePlurals' => null,

    /*
    |--------------------------------------------------------------------------
    | Query Languages
    |--------------------------------------------------------------------------
    |
    | Sets the languages to be used by language-specific settings such as
    | "removeStopWords" or "ignorePlurals". For optimum relevance, it is
    | recommended to only enable languages that are used in your data.
    |
    | Supported: Null, Array
    | Example: ['en', 'fr',]
    |
    */

    'queryLanguages' => ['ar', 'en'],

    /*
    |--------------------------------------------------------------------------
    | Distinct
    |--------------------------------------------------------------------------
    |
    | Using this attribute, you can limit the number of returned records that contain the same
    | value in that attribute. For example, if the distinct attribute is the series_name and
    | several hits (Episodes) have the same value for series_name (Laravel From Scratch).
    |
    | Supported(distinct): Boolean
    | Supported(attributeForDistinct): Null, String
    | Example(attributeForDistinct): 'slug'
    */

    'distinct' => null,
    'attributeForDistinct' => null,

    /*
    |--------------------------------------------------------------------------
    | Other Settings
    |--------------------------------------------------------------------------
    |
    | The easiest way to manage your settings is usually to go to your Algolia dashboard because
    | it has a nice UI and you can test the relevancy directly there. Once you fine-tuned your
    | configuration, just use the command `scout:sync` to get remote settings in this file.
    |
    */
    'hitsPerPage' => 100,
    'ranking' => [
        'desc(available_qty)',
        'typo',
        'geo',
        'words',
        'filters',
        'proximity',
        'attribute',
        'exact',
        'custom',
    ],

];
