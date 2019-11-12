
<?php

return [
    'barcode' => 'الباركود',
    'name'=>'اسم الصنف',
    'ar_name'=>'اسم الصنف عربي',
    'price'=>' السعر',
    'price_tax'=>' السعر بالضريبة',
    'date'=>' تاريخ الاضافة',
    'qty'=>'الكمية',
    'available_qty'=>' المتوفر',
    'actions'=>'خيارات',
    'creator'=>'الموظف',
    'generate_barcode'=>'انشاء  باركود',
    'view_item'=>' عرض الصنف',

    'locale'=>app()->getLocale(),


	
	'save_kit'=>'حفظ ',
	'view_products'=>'عرض المنتجات',
	
    'clone' => 'نسخ',
    'show' => 'عرض ',
    'flow' => 'حركة الصنف',
    'edit' => 'تعديل',
    'activate' => 'تفعيل',
    'search' => 'بحث',
    'description'=>'قائمة الاصناف التابعة لك',
    'create'=>"اضافة منتج ",
    'name_en'=>'اسم الصنف (انجليزي)',
    'name_ar'=>'اسم الصنف (عربي)',

    'has_vat_sale'=>'ضريبة بيع',
    'has_vat_purchase'=>'ضريبة شراء',
    'has_serial_number'=>'سيريال نمبر',
    'is_fixed_price'=>' غير  قابل للخصم',
    'is_service'=>'صنف خدمي',

     'vat_sale'=>'ضريبة البيع',
     'vat_purchase'=>'ضريبة الشراء',

    'save_clone'=>'حفظ ونسخ',
    'save_exit'=>'حفظ وخروج',
    'cancel'=>'الغاء العملية',
    'movement'=>[
	    'total_cost' => 'التكلفة الاجمالية',
        'discount'=>'خصم',
        'in'=>'العمليات الداخلة',
        'out'=>'العمليات الخارجة',
        'stock'=>'المخزون',
        'description'=>'وصف العملية',
        'qty'=>'الكمية',
        'user'=>'المستفيد',
        'creator'=>'المحرر',
        'invoice'=>'الفاتورة',
        'price'=>'السعر',
        'value'=>'القيمة',
        'stock_value'=>'القيمة الاجمالية للمخزون',
        'stock_qty'=>'الكمية المتوفرة',
        'cost'=>'تكلفة الوحدة',
        'date'=>'تاريخ',
        'profits'=>'الارباح',
	    'debit'=>'المدين',
	    'credit'=>'الدائن',
	    'balance'=>'الرصيد',
    ],
	
	'create_filter_value' => __('pages/filters.create_value'),
	'kits' => 'صنف تجميعي',
	'create_kit' => 'اضافة صنف تجميعي',
	'items_count'=>'عدد المنتجات',
	'total' => __('pages/invoice.total'),
	'vat' => __('pages/invoice.vat'),
	'tax' => __('pages/invoice.tax'),
	'subtotal' => __('pages/invoice.subtotal'),
	'discount' => __('pages/invoice.discount'),
	'net' => __('pages/invoice.net'),
	'search_barcode' => __('pages/invoice.search_barcode'),
	'categories' => __('sidebar.categories'),
	
	
];
