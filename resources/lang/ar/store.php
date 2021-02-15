<?php

return [

    "app" => [
        "name" => 'متجر المسبار'
    ],
    "common" => [
        "invalid_user_data" => "بيانات الدخول غير صحيحة",
        "add_new" => "اضف جديد",
        "add" => "اضافة",
        "or" => "او",
        "download" => "تحميل",
        "search_in_all_products" => "البحث في كل المنتجات",
        "customer_support" => "خدمة العملاء",
        "back" => "الخلف",
        "select_bank" => "اختر البنك",
        "select_sender_bank" => "اختر البنك المحول منه",
        "select_account" => "اختر حساب",
        'create_new_account' => 'اضافة حساب',
        'select_or_create_account' => 'اختر او اضف حساب',

        "save" => "حفظ",
        'back_to_home' => "عودة للصفحة الرئيسية",
        "completed_message" => "تم تعديل البيانات  بنجاح",
        "title_message" => "نجاح",
        "select" => "اختيار",
        // 'add_new' => "اضافة حساب",
        'loading' => "يتم تحميل البيانات",
        'no_more' => "لا تتوفر المزيد من النتائج",
        'no_results' => "لاتوجد نتائج",
        'resend_otp' => 'اعادة ارسال الرمز',
        "internationalKey" => "966",
        'verification_code' => 'رمز التحقق:',
        "contact_us" => "تواصل معنا",
        "keep_message" => "راسلنا",
        "update" => "تعديل",
        "search_in" => "البحث في"

    ],
    "contact" => [
        'name' => "الاسم",
        'email' => "البريد الإلكتروني",
        'message' => "اكتب رسالتك هنا..",
        'send' => "إرسال",
    ],
    "header" => [
        "search_placeholder" => "ابحث هنا",
        "categories" => 'الفئات',
        'home' => "الرئيسية",
        // here
    ],
    // البلاد:
    // *54564646546546*
    'messages' => [

        "notify_unpaid_order_message" =>
        "تذكير..\n سوف تنتهي مهلة سداد الطلب رقم (:ORDERID) قريبا \n يرجى السداد قبل \n:DATE\n:TIME",
        'as_your_request' => "1- بناء على طلبك",
        'not_paid' => "1- إنتهاء مهلة السداد",
        "unpaid_order_canceled_message" =>
        "مرحبا *:CUSTOMER_NAME*,\n نأسف، طلبك رقم (:ORDERID) تم الغاءه \n\n سبب الإلغاء:\n:REASON .  ",
        "order_has_been_shipping" => "
تم تسليم الطلب بنجاح، وسوف يتم ارسال الفاتورة لك الآن .
سعدنا بخدمتك",
        "notify_customer_by_new_order_message" => "
مرحبا *:CUSTOMER_NAME*
شكرا لتسوقك من *متجر المسبار*
رقم الطلب: *:ORDER_ID*
المبلغ: *:AMOUNT*

الحسابات البنكية
مصرف الراجحي:
*SA7280000122608010398991*


يرجى السداد قبل
*:DEADLINE_DATE*
*:DEADLINE_TIME*

بعد السداد، فضلا أنقر على الرابط التالي
:PAYMENT_URL

لإلغاء الطلب، أنقر على الرابط التالي
:CANCEL_URL
",
        "order_payment_confirmed" => "
مرحبا *:CUSTOMER_NAME*
تم استلام سداد الطلب #:ORDER_ID بنجاح ، وجاري تجهيز طلبك ، سنقوم باشعارك قريبا
",
        "invalid_order_payment" => "
مرحبا *:CUSTOMER_NAME*
نأسف، عملية السداد للطلب رقم #:ORDER_ID غير صالحة
لمزيد من المعلومات يمكنك التواصل مع خدمة العملاء عبر الرابط التالي",

        "order_shipped_with_shipping_method" => "
مرحبا *:CUSTOMER_NAME*
تم شحن طلبك #:ORDER_ID عبر :SHIPPING_METHOD 
رقم التتبع 
:TRACKING_NUMBER
رابط التتبع
:TRACKING_URL
",
        "order_shipped_with_deivery_man" => "
مرحبا *:CUSTOMER_NAME*
تم شحن طلبك #:ORDER_ID مع مندوب التوصيل
:DELIVERY_MAN
:DELIVERY_MAN_NUMBER
كود  التسليم :CODE
",
        "order_ready_to_pick_up_from_store" => "
مرحبا *:CUSTOMER_NAME*
طلبك #:ORDER_ID جاهز للإستلام
كود الإستلام :CODE
",
        "success" => "نجاح",
        "select_city" => "اختر المدينة",
        "profile_information_updated" => "تم تعديل بيانات الملف الشخصي بنجاح",
        "phone_number_has_been_changed" => " تم تغير رقم الجوال بنجاح",
        "password_has_been_changed" => "تم تغير كلمة المرور بنجاح",
        "confirm" => "تاكيد",
        "are_you_sure" => "هل انت متاكد ؟ ",
        "yes" => "نعم",
        "no" => "لا",
        'bank_account_has_been_created' => "تمت عملية اضافة الحساب البنكي",
        'invalid_otp' => 'رمز التاكيد غير صحيح ',
        "invalid_activity" => "رابط غير صحيح",
        "invalid_activity_message" => "تحاول الولوج لصفحة غير سليمة"
    ],

    "order" => [
        "statuses" => [
            'issued' => 'جديد',
            'pending' => 'معلق',
            'paid'=> 'تم السداد',
            'in_progress'=> 'تحت الاجراء',
            'ready_for_shipping'=> 'جاهز للشحن',
            'shipped'=> 'تم الشحن',
            'delivered'=> 'تم التسليم',
            'canceled'=> 'ملغي',
            'returned'=> 'مرتجع'
        ],
        "id" => "رقم الطلب",
        "amount" => "المبلغ",
        "payment_method" => "وسيلة الدفع",
        "tracking_number" => "رقم التتبع",
        "shipping_method" => "وسيلة الشحن",
        "created_at" => "التاريخ",
        "status" => "حالة الطلب",
        "no_order" => "لاتوجد طلبات",
        "pdf" => "الفاتورة",

        "cancel" => "الغاء الطلب",
        "sales_invoice" => "فاتورة مبيعات",
        "vatId" => 'الرقم الضريبي',
        "taxId" => 'السجل التجاري',
        "phone" => "الهاتف",
        "branch" => "الفرع",
        "online_sales" => "مبيعات الاونلاين",
        "shipping_address" => "عنوان الشحن",
        "total" => "المجموع",
        "tax" => "الضريبة",
        "shipping" => 'الشحن',
        "net" => 'النهائي',
        "paid" => 'مسددة',
        "happy_to_serv_you" => "سعدنا بخدمتك",
        "terms_privacy" => "الشروط والاحكام",
        "terms_list" => [
            "* البضاعة المباعة لاترد ولا تستبدل بعد فتحها .",
            "* الارجاع خلال ثلاثة أيام والتبديل خلال سبعة أيام."
        ],
        "our_address_state" => 'القصيم',
        "draft" => "فاتورة مبدئية",
        "thanks_for_order" => "شكرا لك",
        "created" => "نجاح",
        "instructions_for_payment" => "ستتلقى رسالة واتساب تحتوي على رقم الحساب البنكي ورابط لتاكيد عملية التحويل على رقم الجوال ",
        'payment_confirmation' => "تاكيد عملية الدفع",
        "order" => "الطلب",
        "remmning_time_to_auto_cancel_order" => "الوقت المتبقى حتى الالغاء التلقائي للطلب",
        "transmitter_name" => "اسم المحول",
        "to_blank" => "البنك المحول له",
        "payment_confirmed" => "شكرا لك",
        "payment_confirmed_message" => " سوف يتم اشعارك عند استلام المبلغ",


    ],
    "footer" => [
        "about_us" => "من نحن",
        "contact" => "اتصل بنا",
        "logout" => "تسجيل خروج",
        'privacy' => "سياسة الخصوصية",
        'terms' => "الشروط والأحكام",
        "my_account" => "حسابي",
        "subscribe" => "اشتراك",
        "cart" => "السلة",
        "your_email" => "بريدك الالكتروني",
        "join_news_letter" => "انضم لنشرتنا البريدية",
        "join_news_letter_bio" => "اشترك لتصلك آخر العروض",
        "copyright_saved" => "جميع الحقوق © 2020  محفوظة",
        "address" => "العنوان",
        "phone" => "الهاتف",
        "email" => "البريد الالكتروني",
        "information" => 'من نحن'
    ],
    "profile" => [
        "profile" => "لوحة التحكم",
        "logout" => "تسجيل خروج",

        "new_customer" => "عميل جديد",
        "exists_customer" => "لدي حساب ",

        "login" => "تسجيل دخول",
        "phone_number" => "الجوال",
        "country" => "الدولة",
        "or_create_new_account" => "أنشئ حساب جديد",
        "forget_password" => "نسيت كلمة المرور",
        "password" => "كلمة المرور",
        "new_password" => "كلمة المرور الجديدة",
        "first_name" => "الأسم الأول",
        "last_name" => "أسم العائلة",
        "sign_up" => "انشاء حساب",
        "account_number" => "رقم الحساب",
        'my_info' => 'معلوماتي',
        'orders' => "الطلبات",
        "mobile" => "الجوال",
        "addresses" => 'العناوين',
        "payments" => "المدفوعات",
        "email_address" => "البريد الالكتروني",
        "date" => "التاريخ",
        "order" => "الطلب",
        "status" => "الحالة",
        "amount" => "المبلغ",
        "operations" => "العمليات",
        "detail" => "التفاصيل",
        "old_password" => "كلمة المرور الحالية",
        "password_confirmation" => "تأكيد كلمة المرور",
        "name" => "الاسم",
        "state" => "المنطقة",
        "city" => "المدينة",
        "address" => "الوصف",
        "zip" => "الرمز البريدي",
        "edit" => "تحرير",
        "already_have_account" => "هل لديك حساب مسبقا ؟",
        "reset_password" => "تغيير كلمة المرور",
        'confirm_otp' => 'ادخل الرمز',
        "street_name" => "الشارع",
        "area" => "الحي",
        "tel" => "الهاتف"

    ],
    'cart' => [
        'cart_empty' => "السلة فارغة",
        'shopping_here' => "تسوق من هنا ",
        'checkout' => "اتمام الطلب",
        "image" => "الصورة",
        "product_name" => "اسم المنتج",
        "price" => "السعر",
        "quantity" => "الكمية",
        "total" => "الإجمالي",
        "login_to_checkout" => "إتمام الشراء",
        "inc_vat" => "شامل الضريبة",
        "shipping_address" => "عنوان الشحن",
        "shipping_method" => "طريقة الشحن",
        "bank_transfer" => "تحويل بنكي",
        "mada" => "مدى",
        "visa" => "فيزا",
        "sdad" => "سداد",
        "confirm_order" => "تأكيد الطلب",
        "you_order_processed" => "سوف يتم ارسال تعليمات السداد الى رقم الواتساب التالي",
        "select_address" => 'أختر عنوان الشحن',
        "create_address" => "أضافة عنوان",
        "free" => "مجانا",
        "shipping_weight" => "وزن الشحنة",
        "shipping_total" => "تكلفة الشحن",
        "net" => "الصافي",
        'create_shipping_address' => 'اضافة عنوان',
        'select_shipping_address' => 'اختر عنوان الشحن',

        'back_to_cart' => 'الرجوع الى السلة'

    ],
    'products' => [
        'warranty_subscription' => "الضمان",
        "all_of_them" => "الكل",
        'inc' => 'شامل الضريبة',
        'subcategories' => 'الاقسام الفرعية',
        'sorting_via' => 'الترتيب حسب',
        'tags' => 'لنتائج أكثر دقة.. أنقر على التاغ',
        'show_more' => 'عرض المزيد',
        'in' => 'في قسم',
        'products' => 'المنتجات',
        'products_count' => 'عدد المنتجات',
        'all_products_count' => 'كل المنتجات',
        'product' => 'منتج',
        'show_all' => "عرض الكل",
        'add_to_cart' => 'أضف الى السلة',
        'remove_to_cart' => 'أحذف من السلة',
        'product_specifications' => 'مواصفات المنتج',
        'out_of_stock' => 'غير متوفر',
        'sar' => 'ر.س',
        "kg" => "كيلو",
        "name" => "اسم المنتج",
        "quantity" => "الكمية",
        "price" => "السعر",
        "total" => "الاجمالي",
        'model' => "الموديل",
        "description" => "الوصف",
        'filters' => 'فلاتر البحث',
        "apply" => "تطبيق",
        "reset" => "مسح",
        "filters_for_search" => "فلاتر بحث ... ",
        "barcode" => "الباركود",
        "related_products" => "منتجات ذات من صلة",
        "sorting_low_price" => "الاقل سعراً",
        "sorting_high_price" => "الاعلى سعراً",
        "sorting_most_sellers" => "الاكثر مبيعاً",
        "sorting_lastest" => "الاحدث",
        "sorting_oldest" => "الاقدم",
        "sorting_only_available" => "المتوفر فقط",
        'more_than' => 'اعلى من ',
        "all" => 'جميع',
        'prices' => "الاسعار",
        'category' => 'القسم',
        "remove_all" => "مسح الكل",
        "special_offer" => "الحق على العرض الخاص",
        "time_days" => "يوم",
        "time_hrs" => "ساعة",
        "time_mins" => "دقيقة",
        "time_secs" => "ثانية",
        "sale_for" => "عرض",
        "agent_warrnaty" => "ضمان الوكيل",
        "new_arrival" => "وصل حديثاً",

    ],
    'content' => [
        'about_us_content' => '<h4 class="content__title">من نحن</h4>مؤسسة بيت المسبار التجارية
        السجل التجاري: 847200311
        الرقم الضريبي: 300006662230103
        متجر المسبار هو موقع الكتروني مختص في بيع الأجهزة الالكترونية والرقمية الحديثة من قبيل الحواسيب والساعات الذكية، الهواتف وأيضا بعض المنتجات الرقمية كبطاقات شحن وأنظمة حماية وغيرها حيث يفتخر موقعنا بكونه رائدا وشاملا في هذا المجال بأثمنة جد تنافسية مقارنة بالمتاجر الأخرى في السوق.
        اكتسب متجر المسبار مند انطلاقته على ثقة كافة المستخدمين وذلك بفضل تميزه بتقديم متجر بأحدث المعايير التكنولوجية والبرمجية لتسهيل عرض المنتجات والوصول اليها وكذلك بفضل تقديم منتجات في غاية الجودة مع ضمان حق المشتري في حال أراد ارجاع او استبدال المنتج.        
        ولم يكن لنا ان نحصد هذا النجاح الا بدعم وثقة عملائنا، ولأنهم دائما لديهم الرغبة المستمرة في التميز فنسعى دائما لتطوير المتجر بكل ما هو جديد ومواكب لتلبية طموحات عملائنا.
        <h4 class="content__title">قيمنا</h4>ايمانا منا بجعل تجربة التسوق على الانترنت تجربة فريدة ومميزة جعلنا متجر المسبار بشكل مبسط، حيث سيجد المشتري في منصتنا التنوع في المنتجات والسهولة في اختيارها والبحث عنها، كما سيجد فريق دعم فني جاهز على مدار اليوم وطيلة أيام الأسبوع للإجابة عن تساؤلات زوارنا الكرام والمساعدة في حال مشكلاتهم.
        وقد بذل طاقم متجر المسبار جهده في جعل عملية التسوق لدينا ممتعه وسهله وسريعة وتوفر كل ما يحتاجه العميل مع التكفل بعملية الشحن الى غاية منزلك إذا كنت بداخل مدينة الرياض بالإضافة الى اننا نقدم عروض وخصومات أسبوعيا لكل زبنائنا الاوفياء.
        <h4 class="content__title">استراتيجيتنا</h4>يسعى متجر المسبار مند انطلاقته على الاهتمام بجانب الجودة حيث لا نقدم أي منتجات لا ترقى لمعايير الجودة التي تفرضها المملكة العربية السعودية او أي منتجات لها أي ضرر على المستخدمين وعلى سلامتهم حيث اننا دائما نحث موردينا على احترام معايير السلامة سواء في التصنيع وذلك عبر عدم استعمال مواد خطيرة ومسرطنة او في الاستعمال بحيث لا نعرض منتجات غير امنة في الاستعمال على الافراد وخصوصا الأطفال.
        ومن ضمن اهدافنا ايضا جعل متجر المسبار عنوانا للتميز والجودة وايضا سهولة الاستخدام لذلك نحرص على جعل الشراء من متجرنا تجربة فريدة وذلك من خلال الاعتماد على  واجهة سهلة الاستخدام User Friendly تتيح لزوارنا التنقل بين ارجاء الموقع بسهولة، بالإضافة الى ان منصتنا Responsive  أي قابلة للعرض على كافة الأجهزة سواء أجهزة سطح المكتب او الموبايلات وأجهزة التابلت، وما يميز منصتنا أيضا هو التواصل المستمر مع الزوار الكرام سواء عبر صفحة اتصل بنا والتي يمكن للجميع استعمالها للوصول الينا او عبر صفحاتنا في مواقع التواصل الاجتماعي مما يجعل موقعنا مواكبا لاهتمامات زوراه الكرام وهو ما يطلق عليه أيضا User Feedback.
        <h4 class="content__title">كيف يمكنني الشراء من متجر المسبار ؟</h4>تفضل بزيارة الموقع باللغة العربيّة او الإنجليزية واختر القسم المناسب الذي الشراء من او مباشرة المنتجات التي ترغب بشرائها المعروضة في الرئيسية عن طريق الضغط على المنتج وقراءة كافة تفاصيله ومن ثم الضغط على أضف إلى السلة وأكمل عملية الدفع بالوسيلة المفضلة لديك أو اختر بين "الاستمرار بالتسوق"، إذا كنت ترغب بإضافة المزيد من المنتجات أو "المواصلة لأنهاء الشراء "، إذا كنت قد أضفت كافة المنتجات التي ترغب بها.
        ويمكنك القيام بإنشاء عضوية جديدة، إذا كنت مستخدماُ جديداً أو سجّل دخولك إلى متجر المسبار باستخدام البريد الإلكتروني وكلمة السر الخاصة بك.',
        "terms_and_conditions_content" => '<h4 class="content__title">الشروط والأحكام</h4>باستعمالك للمتجر فانت توافق ضمنيا على الشروط والاحكام الموضحة في هذه الصفحة، هذه الشروط قد تتغير من حين لأخر لذلك احرص على زيارة الصفحة بتكرار.
        كافة الشروط والبنود المبينة في هذه الصفحة لا تتنافى مع أي من القوانين الجاري بها العمل محليا أو قوانين انتهاك الخصوصية والملكية الفكرية والملكية المشتركة المعمول بها دوليا. ويمكنكم معرفة المزيد عن هذا عبر زيارة صفحة "سياسة الخصوصية"
        البنود الواجب عليك الالتزام بها:<h4 class="content__title">(1) تقديم </h4>مرحبا بكم في متجر المسبار، فيما يلي الشروط والأحكام التي تخص استخدامك ودخولك لصفحات موقع إن دخولك واستخدامك لموقعنا هو موافقة منك على القبول ببنود وشروط هذه الاتفاقية والتي تشمل كافة التفاصيل أدناه وهو تأكيد لالتزامك بالاستجابة لمضمون هذه الاتفاقية الخاصة بـمتجر المسبار والمشار إليه فيما يلي باسم "نحن" والمشار إليها أيضا بالـ "الموقع"، في باقي بنود " الشروط والأحكام" وتعتبر هذه الاتفاقية سارية المفعول حال دخولك للموقع.<h4 class="content__title">(2) العضوية</h4>
        1. لا يحق لأي شخص استخدام الموقع إذا ألغيت عضويته من متجر المسبار .
        2. في حال قيام أي مستخدم بالتسجيل كمؤسسة تجارية، فإن مؤسسته التجارية تكون ملزمة بكافة الأحكام والشروط الواردة في هذه الاتفاقية.
        3. ينبغي عليك الالتزام بكافة القوانين المعمول بها لتنظيم التجارة عبر الانترنت.
        4. لا يحق لأي عضو أو مؤسسة أن تقوم بفتح حسابين في آن واحد لأي سبب كان، ولإدارة الموقع الحق بتجميد الحسابين أو إلغاء أحدهما مع الالتزام بتصفية كافة الالتزامات المالية المتعلقة بالحساب قبل إغلاقه.<h4 class="content__title">(3) التزامات التسجيل</h4>فور تقديم طلب التسجيل للحصول على عضوية في الموقع تكون مطالباً بالإفصاح عن معلومات محددة واختيار اسم مستخدم وكلمة مرور سرية لاستعمالها عند الدخول للموقع. وعند تنشيط حسابك ستعتبر عضوا بالموقع وبذلك تكون قد وافقت على
        1. أن تكون مسؤولاً عن المحافظة على سرية معلومات حسابك وكلمة المرور السرية. وتكون بذلك موافقاً على إعلام متجر المسبار حالاً بأي استخدام غير مفوض به لكلمة دخولك أو حسابك أو أي اختراق آخر لمعلوماتك السرية.
        2. لن يكون متجر المسبار بأي حال من الأحوال مسؤولاً عن أي خسارة قد تلحق بك بشكل مباشر أو غير مباشر معنويا أو ماديا نتيجة كشف معلومات اسم المستخدم أو كلمة الدخول.
        3. بتسجيلك عضوية في موقع المسبار تعتبر المسؤول الحصري والوحيد عن أي خسائر مباشرة أو غير مباشرة قد تلحق بـمتجر المسبار نتيجة أي استخدام غير شرعي أو مفوض لحسابك من طرفك أو من طرف أي شخص آخر حصل على مفاتيح الوصول إلى حسابك بالموقع سواء كان بتفويض منك أو بدون تفويض.
        4. أنت موافق على الإدلاء بمعلومات حقيقية وصحيحة ومحدثة وكاملة عن نفسك حسبما هو مطلوب في استمارة التسجيل لمتجر المسبار.
        5. لا يقدم متجرنا استبدال او استرجاع للمنتجات الرقمية.
        6. يلتزم متجر المسبار بالتعامل مع معلوماتك الشخصية وعناوين الاتصال بك بسريّة تامة.
        7. سوف تكون ملزماً بالحفاظ على بيانات التسجيل وتحديثها دائما للإبقاء عليها حقيقية وصحيحة وراهنة وكاملة، وإذا ما أفصحت عن معلومات غير حقيقية أو غير صحيحة أو غير راهنة أو غير كاملة او مخالفة لما جاء في اتفاقية الشروط والأحكام، فإن لـمتجر المسبار الحق كاملاً في وقف أو تحديد أو إلغاء عضويتك وحسابك في الموقع، وذلك دون الحاق الأضرار بحقوق متجر المسبار الأخرى ووسائله المشروعة في استرداد حقوقه.
        8. لـمتجر المسبار مطلق الإرادة وفي أي وقت أن يجري أي تحقيقات يراها ضرورية (مباشرة أو عبر طرف ثالث) ويطالبك بالإفصاح عن معلومات إضافية أو وثائق مهما كان حجمها لإثبات هويتك و/أو ملكيتك لأدواتك المالية.
        9. في حالة أن المقدم لطلب التسجيل يمثل مؤسسة تجارية فلابد من تزويد كافة المعلومات والوثائق المطلوبة التي تتضمن رخصتك التجارية، ووثائق أخرى للمؤسسة و/أو وثائق تظهر مسؤولية أي شخص يتصرف نيابة عنك.
        10. في حالة عدم الالتزام بأي مما ورد أعلاه فإن لإدارة متجر المسبار الحق في إيقاف أو إلغاء عضويتك وحجبك عن الموقع. ونحتفظ كذلك بالحق في إلغاء أي حسابات غير مؤكدة وغير مثبتة أو عمليات أو حسابات مر عليها مدة طويلة دون نشاط.<h4 class="content__title">(4) التعديل على الشروط والأحكام </h4>
        1. أنت تعلم وموافق على أن يقوم متجر المسبار بأي تعديل على اتفاقية الشروط والأحكام، وبموجبه تتضاعف التزاماتك أو تتضاءل حقوقك وفقاً لأي تعديلات قد تجرى على اتفاقية الشروط والأحكام دون الحاجة الى اعلامك.
        2. أنت توافق على ان متجر المسبار يملك بمطلق صلاحيته ودون تحمله المسؤولية القانونية أن يجري تعديلات أساسية أو فرعية على هذه الاتفاقية دون أن يتطلب ذاك موافقة إضافية من طرفك، وذلك في أي وقت وبأثر فوري، دون إلزام بإخبارك، ويمكنك دائما معرفة التعديلات عبر الرجوع لهذه الصفحة.
        3. أنت توافق على ان متجر المسبار يعتبر موقع إلكترونيا تقنيا على شبكة الانترنت يتيح شراء منتجات الكترونية ورقمية.
        4. العضوية على الموقع مجانية.
        5. لا يوجد شحن للمنتجات الرقمية، الا ادا اشتريت منتجات الكرتونية تتطلب الشحن.
        6. كافة الرسوم تحتسب بالريال السعودي، عليك دفع كافة الرسوم المستحقة على عملياتك بالموقع مضافاً إليها أي نفقات أخرى يضيفها متجر المسبار، على أن يتم السداد بواسطة الوسائل المعتمدة لذلك.
        7. في حال عدم التزامك بدفع الرسوم أو النفقات المحتسبة على عملياتك في الموقع وتجاوزت المدة 3 أيام عمل، فأن متجر المسبار وبدون أدنى مسؤولية قانونية يحتفظ بحقه في إلغاء طلب الشراء الخاصة بك.<h4 class="content__title">(5) الدفع للمشتريات</h4>
        1. نظام الدفع في متجر المسبار يمكن أن يتم عبر الانترنت كليا من خلال خيارات الدفع المتوفرة على متجر المسبار وهي نظام المدفوعات سداد والدفع عن طريق البطاقات الائتمانية أو من خلال أي طريقة دفع يوفرها متجر المسبار على من حين لآخر.
        2. لمتجر المسبار كافة الصلاحيات في اختيار المنتج المعروض والسعر الملائم لكل منتج حسب ما يراه الموقع مناسبا.
        3. ينتهي دور متجر المسبار بمجرد استلامك لمشترياتك. في حالة الاستبدال أو الاسترجاع عليك مخاطبة المتجر مباشرة.<h4 class="content__title">(6) معلوماتك الشخصية</h1>
        1. توافق على ان لمتجر المسبار الحق في التصرف في معلوماتك ضمن ما هو محدد في هذه الصفحة وفي صفحة سياسة الخصوصية، بشكل دائم وغير قابل للإلغاء، وذلك من خلال التسجيل، الشراء، او استعمال النماذج المخصصة للتواصل والتسجيل، أو عبر أية رسالة إلكترونية أو أي من قنوات الاتصال المتاحة بالموقع. وذلك بهدف تشغيل وترويج الموقع وفق اتفاقية إخلاء المسؤولية وبيان الخصوصية.
        2. أنت الوحيد المسؤول عن المعلومات التي قمت بإرسالها أو نشرها وينحصر دور متجر المسبار بالسماح لك بعرض هذه المعلومات على صفحات الموقع ومن خلال قنواته الإعلانية.<h4 class="content__title">(7) سرية المعطيات </h4>
        1. يتخذ متجر المسبار معايير (ملموسة وتنظيمية وتكنولوجية) للحماية من وصول شخص غير مفوض إلى معلومات هويتك الشخصية، وحفظها. مع العلم أن الانترنت ليس وسيلة آمنة،
        2. المسبار ليس له سيطرة على أفعال أي طرف ثالث، مثل صفحات الانترنت الأخرى الموصولة بهذا الموقع، أو أطراف ثالثة تدعي أنها تمثلك وتمثل آخرين.
        3. أنت تعلم وموافق أن متجر المسبار قد يستخدم معلوماتك التي زودته بها، بهدف تقديم الخدمات لك في متجر المسبار، ولإرسال رسائل تسويقية لك، وان بيان الخصوصية في هذا الموقع يضبط عمليات الجمع والمعالجة والاستخدام والتحويل لمعلومات هويتك الشخصية.<h4 class="content__title">(8) نقض اتفاقية الشروط والأحكام</h4>
        ان متجر المسبار بحسب اتفاقية الشروط والأحكام وبحسب القانون قد يلجأ إلى وقف مؤقت أو دائم أو سحب وإلغاء عضويتك و/أو تحديد أو إلغاء وصولك إلى الموقع دون الإضرار بحقوقه الأخرى ووسائله المشروعة في استرداد حقوقك في حالة
        1. إذا انتهكت اتفاقية الشروط والأحكام.
        2. إذا لم يكن بإمكان متجر المسبار التأكد من صحة أي من معلوماتك المقدمة إليه.
        3. إذا قرر متجر المسبار أن نشاطاتك قد تتسبب لك أو لمستخدمين آخرين ولـمتجر المسبار في إشكالات قانونية.
        4. قد يلجأ متجر المسبار "بحسب تقييمه " إلى إعادة نشاط المستخدمين الموقوفين، حيث أن المستخدم الذي أوقف نشاطه نهائياً أو سحبت عضويته، قد لا يكون بإمكانه التسجيل أو محاولة التسجيل في متجر المسبار أو استخدام الموقع بأي طريقة كانت مهما كانت الظروف، لحين السماح له بإعادة نشاطه في متجر المسبار. ومع ذلك فإنك إن قمت بانتهاك اتفاقية الشروط والأحكام هذه، يحتفظ المتجر بحقه في استعادة أية مبالغ مستحقة للموقع عليك، وأي خسائر وأضرار تسببت بها لـمتجر المسبار كما أن له الحق باتخاذ الإجراءات القانونية و/أو اللجوء للقضاء لرفع قضية جنائية او تجارية ضدك حسبما يراه متجر المسبار مناسباً.<h4 class="content__title">(9) حقوق المتجر</h4>
        إن قيامك أو قيام آخرين بانتهاك الاتفاقية هذه لا يلزم متجر المسبار بالتنازل عن حقه في اتخاذ الإجراءات المناسبة لمثل هذا الفعل ولغيره من أفعال الانتهاك المشابهة. ومتجر المسبار لا يضمن اتخاذه إجراءات ضد كل الانتهاكات لاتفاقية الشروط والأحكام.<h4 class="content__title">(10) محتويات غير قانونية </h4>
        1. كعضو مسجل في الموقع، يمنع عليك الإعلان عن أرقام هواتفك أو بريدك الإلكتروني من خلال أي جزء من الموقع، ويعتبر ذلك بمثابة انتهاك للشروط والأحكام.
        يجوز لكم فقط الاستفادة من الموقع لأغراض مشروعة وبصورة قانونية. ويجب عليكم الالتزام بجميع القوانين المعمول بها كما انه ممنوع عليكم استخدام منتجاتنا في اي اعمال غير قانونية ومضللة كما انه ممنوع عليكم استعمال أي جزء من متجرنا او صورة او محتوى رقمي كيف ما كان في أي موقع اخر دون موافقة كتابية من إدارة المتجر.
        <h4 class="content__title">(11) التغذية الرجعية</h4>
        متجرنا يشجع جميع الأعضاء على الموقع لتقديم اقتراحات أو تغذية رجعية (فيد باك)، وسوف تعرض على الموقع في أي مكان تحدده إدارة الموقع.<h4 class="content__title">(12) إلغاء الوصول و/أو العضوية</h4>
        بدون إلحاق الضرر بحقوقه الأخرى ووسائله المشروعة لاسترداد حقوقه، يمكن لـمتجر المسبار وقف أو إلغاء عضويتك و/أو وصولك إلى الموقع في أي وقت وبدون إنذار ولأي سبب، ودون تحديد، ويمكنه أيضا إلغاء اتفاقية الشروط والأحكام هذه.<h4 class="content__title">(13) الضمان</h4>
        يوفر متجرنا منتجات رقمية والكرتونية  بالكمال وبالتالي فانه من الصعب علينا ان نقدم ضمانات على كل المنتجات التي نقدمها او نضمن استبدالها او استرجاعها, كل منتجاتنا الرقمية لا تخضع لسياسة الضمان او الاسترجاع وفي حال قمنا بإضافة منتجات عليها ضمان مستقبلا فسوف نشير الى دلك ضمن الشروط او في وصف المنتج.<h4 class="content__title">(14) تحديد المسؤوليات</h4>
        بحسب ما هو مسموح في القانون فإن متجرنا وموظفيه ومدراءه ووكلائه والمتفرعين عنه والمجهزين له لن يكونوا مسؤولين عن أي خسارة مباشرة أو غير مباشرة أو أعطال تنشأ عن استخدامك للموقع. إذا لم تكن راض عن الموقع أو عن أي من محتوياته فالحل وهو بعدم استمرارك باستخدامه، علاوة على ذلك فأنت موافق أن أي استخدام غير مفوض للموقع وخدماته، بسبب إهمالك سوف يتسبب بإلحاق الأذى بالـمتجر المسبار، وعليه سيضطر الموقع حينها إلى اللجوء إلى الشروط والأحكام.<h4 class="content__title">(15) الأمان </h4>
        أنت موافق على استخدامك الامن لمتجر المسبار مع الحفاظ على امن مدراءه وموظفيه ووكلائه ومجهزيه ووقايتهم من أي ضرر قد يلحق بهم جراء مطالبات وخسائر وأعطال وتكاليف ونفقات، تحدث بسبب انتهاكك لاتفاقية الشروط والأحكام أو خرقك لأي قانون أو تعديلات أو تعدي على حقوق أطراف ثالثة.<h4 class="content__title">(16) العلاقة والإشعارات</h4>
        لا تتضمن أي من بنود اتفاقية الشروط والأحكام إشارة إلى وجود شراكة بينك وبين متجر المسبار. وأنت ليس لك سلطة في إلزام متجر المسبار بأي حال من الأحوال، وأن أي إشعارات تود إرسالها لـمتجر المسبار، عليك إرسالها بالبريد الإلكتروني، على أن يقوم متجر المسبار بالرد على الرسالة الإلكترونية. أنت تعرف وموافق على أن أية إشعارات ترسل إليك من متجر المسبار سوف تعلن على الموقع أو بواسطة البريد الإلكتروني الذي زودتنا به خلال عملية التسجيل.<h4 class="content__title">(17) تحويل الحقوق والالتزامات</h4>
        أنت هنا تمنح متجر المسبار الحق في تحويل جزء أو كل حقوقه ومنافعه والتزاماته ومسؤولياته، إلى أطراف أخرى تعمل معه، دون الحاجة للرجوع إليك، وذلك بحسب نصوص اتفاقية الشروط والحكام، ومتجر المسبار ملتزم بإشعارك عن مثل هذه التحويلات إذا حصلت وكذلك بالنشر على الموقع.
        وتحتفظ المسبار بحقها في الإبلاغ عن أي نشاط إذا شكت أنه يخالف أي قانون من القوانين المعمول بها، وذلك إلى مسؤولي تنفيذ القانون المعنيين أو الجهات الرقابية أو الغير. ولأجل التعاون مع الطلبات الحكومية لحماية المسبار وعملائها أو لضمان نزاهة سير أعمال المسبار وأنظمتها، يجوز لالمسبار الدخول إلى والإفصاح عن أي معلومات تراها ضرورية أو مناسبة، بما في ذلك على سبيل المثال لا الحصر بيانات الحساب وبيانات الاتصال الخاصة به وعنوان بروتوكول الإنترنت ومعلومات مرور البيانات وتاريخ الاستخدام والمحتوى المنشور. ويجب على كل من المسبار والمستخدم حماية بيانات العملاء حسب سياسات كل منهم والقوانين المعمول بها في الدولة المختارة.<h4 class="content__title">(18) معلومات عامة</h4>
        إذا كانت أية فقرة واردة في اتفاقية الشروط والأحكام هذه غير صالحة أو ملغاة أو أنها لأي سبب لم تعد نافذة، فإن مثل هذه الفقرة لا تلغي صلاحية بقية الفقرات الواردة في الاتفاقية. هذه الاتفاقية (والتي تعدل بين حين وآخر بحسب بنودها) تضع كافة الخطوط العريضة للتفاهم والاتفاق بينك وبين متجر المسبار مع الاعتبار لما يلي
        -ليس من حق أي شخص لا يكون طرفاً في هذه الاتفاقية أن يفرض أية بنود أو شروط فيها.
        -إذا تمت ترجمة اتفاقية الشروط والأحكام لأي لغة أخرى، سواء على الموقع أو بطرق أخرى، فإن النص العربي لها يظل هو السائد.<h4 class="content__title">(19) القانون والتشريع الحاكمان </h4>
        اتفاقية الشروط والأحكام هذه محكومة ومصاغة بحسب قوانين تنظيم الاستخدام الامن للمعطيات الرقمية الاوربية ولا تتنافى مع القوانين المحلية او الدولية المعمول بها في هدا المجال.',
        "privacy_content" => '<h4 class="content__title">سياسة الخصوصية</h4>نقدر مخاوفكم واهتمامكم بشأن خصوصية بياناتكم على شبكة الإنترنت وعلى متجر المسبار لقد تم إعداد هذه السياسة لمساعدتكم في تفهم طبيعة البيانات التي نقوم بتجميعها عنكم عند زيارتكم لموقعنا على شبكة الانترنت وكيفية تعاملنا مع هذه البيانات الشخصية.
        باستخدامك لمتجر المسبار انت توافق على كل بنود هذه الصفحة وعلى بنود صفحة "شروط والاحكام" الموضحة على الموقع، وتلزمك هذه البنود بتقبل كافة الشروط والبنود المطبقة عليك مند دخولك للموقع الى حين الخروج منه.
        تلتزم إدارة الموقع، وفق القوانين المنظمة، ووفق قوانين حماية المعطيات والتي تقرها المنظمات الدولية بعدم كشف أي معلومات شخصية عن الزائر / المتصفح كالايميل او العنوان وغيرها من المعلومات.
        ويلتزم متجر المسبار بعدم تبادل، أو تداول أي من تلك المعلومات أو بيعها لأي طرف آخر طالما كان ذلك في حدود قدرات إدارة الموقع الممكنة، ولن يُسمح بالوصول إلى المعلومات إلا للأشخاص المؤهلين والمحترفين الذين يشرفون على متجر المسبار.<h4 class="content__title">اخلاء المسؤولية</h4>
        يقر المستخدم/الزائر لموقعنا على انه المسؤول الحصري والوحيد على طبيعة الاستخدام لموقعنا وعلى سلامة المعطيات والمعلومات التي يدخلها او يقدمها للموقع, وتخلي ادارة متجر المسبار مسؤوليتها من كل ضرر او تسريب او اختلال او مصاريف يتكبدها المستخدم أو يتعرض لها جراء استخدام متجر المسبار.
        <h4 class="content__title">التصفح</h4>لا يقوم متجر المسبار بتجميع اي معطيات عنك أثناء تصفحك للموقع كملفات الكوكيز (Cockies)  الخاص بك او اي معلومات اخرى دون ارادتك, لكن جراء استخادمنا لخدمة Google Analytics كطرف ثالت قد يتم تجميع بضع معلومات عنك والتي تحددها جوجل في صفحتها هذه -  .سياسة الخصوصية<h4 class="content__title">عنوان بروتوكول شبكة الإنترنت (IP)</h4>
        في أي وقت تزور فيه اي موقع انترنت بما فيها متجر المسبار سيقوم السيرفر المضيف بتسجيل عنوان بروتوكول شبكة الإنترنت (IP) الخاص بك، تاريخ ووقت الزيارة ونوع متصفح الإنترنت الذي تستخدمه والعنوان URL الخاص بأي موقع من مواقع الإنترنت التي تقوم بإحالتك إلى الى هذا الموقع على الشبكة.
        <h4 class="content__title">الروابط الخارجية والاعلانات على الموقع</h4>
        قد يشتمل موقعنا على روابط لمواقع أخرى على شبكة الإنترنت. او اعلانات من شركات اعلانية مثل Google AdSense ولا نعتبر مسؤولين عن أساليب تجميع البيانات من قبل تلك المواقع، يمكنك الاطلاع على سياسات الخصوصية والمحتويات الخاصة بتلك المواقع التي يتم الدخول إليها من خلال أي رابط ضمن هذا الموقع.
        قد نستعين بشركات إعلانية او أطراف ثالثة لعرض الإعلانات او تقديم خدمات ويحق لهده الأطراف جمع بعض المعلومات عندما تزور موقعنا. (باستثناء الاسم أو العنوان أو عنوان البريد الإلكتروني أو رقم الهاتف)، وذلك من أجل تقديم إعلانات حول البضائع والخدمات التي تهمك.<h4 class="content__title">إفشاء المعلومات</h4>
        سنحافظ في كافة الأوقات على خصوصية وسرية كافة البيانات الشخصية التي نتحصل عليها. ولن يتم إفشاء هذه المعلومات إلا إذا كان ذلك مطلوباً بموجب أي قانون أو مذكرة قضائية، وفقط عندما نعتقد بحسن نية أن مثل هذا الإجراء سيكون مطلوباً أو مرغوباً فيه للتمشي مع القانون، أو للدفاع أو حماية حقوق الملكية الخاصة بهذا الموقع أو الجهات المستفيدة منه.<h4 class="content__title">تحديد المسؤوليات</h4>
        يتحمل المتصفح/ الزائر كامل المسؤولية عن جميع المعطيات الخاصة به، التي يرفعها وينشرها عبر موقعنا.        
        يلتزم المشترك بعدم محاولة ولوج او قرصنة او تعديل أي معلومات عنه او عن أعضاء اخرين او عن الإدارة ليس له صلاحية في ولوجها وفي حال تم ذلك يبقى للموقع وادارته كامل الحقوق في المتابعة قضائيا.
        يلتزم الزائر بكل شروط الاستخدام، وبالا يستعمل متجر المسبار او أي شيء يدخل ضمن موقعنا في أي شيء يخالف الشريعة الإسلامية بأي شكل من الأشكال، أو في أغراض غير قانونية، على سبيل المثال لا الحصر، مثل القرصنة ونشر وتوزيع مواد أو برامج منسوخة، أو الخداع والتزوير أو الاحتيال أو التهديد أو إزعاج أي شخص أو شركة أو جماعة أو نشر فيروسات أو ملفات تجسس أو وضع روابط إلى مواقع تحتوي على مثل هذه المخالفات..
        يمنع انتهاك حقوق الملكية الفكرية أو تشويه سمعة شخص أو مؤسسة أو شركة أو تعمد نشر أي معلومات تسبب ضررا لشركة أو شخص أو دولة أو جماعة وعدم وضع مواد قرصنة وبرامج مسروقة وجميع ما يخالف الأعراف والقوانين المنظمة، ويكون المشترك مسؤولا مسؤولية كاملة عن كل ما يقدمه عبر حسابه على الموقع.<h4 class="content__title">التعديلات على البنود</h4>
        نحتفظ بالحق في تعديل بنود وشروط سياسة سرية وخصوصية المعلومات إن لزم الأمر ومتى كان ذلك ملائماً. سيتم تنفيذ التعديلات هنا او على صفحة الشروط والأحكام وقد يتم او لا يتم بصفة مستمرة إخطارك بالبيانات التي حصلنا عليها، وكيف سنستخدمها والجهة التي سنقوم بتزويدها بهذه البيانات.<h4 class="content__title">الاتصال بنا</h4>
        يمكنكم الاتصال بنا عند الحاجة من خلال الضغط على رابط اتصل بنا المتوفر في روابط موقعنا او الارسال الى بريدنا الالكتروني.<h4 class="content__title">اخيرا</h4>
        إن مخاوفك واهتمامك بشأن سرية وخصوصية البيانات تعتبر مسألة في غاية الأهمية بالنسبة لنا. نحن نأمل أن يتم تبديد كل مخاوفك وتوضيح مسارات كافة معطياتك من خلال هذه السياسة.
        في حال كنت غير راضي عن أي شيء ضمن هذه الصفحة او في صفحة شروط والاحكام, المرجوا التوقف عن استخدام موقعنا.',

    ]
];
