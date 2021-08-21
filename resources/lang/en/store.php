<?php

return [
    'app' => [
        'description' => '',
        'name' => 'Almesbar Shop',
    ],
    'header' => [
        'search_placeholder' => 'search here..',
        'categories' => 'Categories',
        'home' => 'Home',
    ],

    'common' => [
        'follow_offers' => 'follow our offers',
        'next' => 'Next',
        'invalid_user_data' => 'invalid login credentials',
        'add_new' => 'Add New',
        'or' => 'Or',
        'add' => 'Add',
        'ok' => 'Ok',
        'customer_support_note' => '* If you are unable to open links, please save 0163394000 in your contacts',
        'downlod' => 'Download',
        'search_in_all_products' => 'Search in all products',
        'search_in' => 'Search In',
        'customer_support' => 'Customer Service',
        'update' => 'Update',
        'select_bank' => 'Select Bank',
        'select_sender_bank' => 'Select Your Bank',
        'select_account' => 'Select Account',
        'create_new_account' => 'Add Account',
        'select_or_create_account' => 'Select/Add Account',

        'back' => 'Back',
        'save' => 'Save',
        'back_to_home' => 'Back to home',
        'completed_message' => 'Information Updated',
        'title_message' => 'Success',
        'select' => 'Select',
        // 'add_new' => "Add Account",
        'loading' => 'Loading Data',
        'no_more' => 'No more result',
        'no_results' => 'No result',
        'resend_otp' => 'resend otp',
        'internationalKey' => '966',
        'verification_code' => 'Verification Code:',
        'contact_us' => 'Contact Us',
        'keep_message' => 'Send Message',
    ],
    'contact' => [
        'name' => 'Name',
        'email' => 'E-mail Address',
        'message' => 'Type your message..',
        'send' => 'Send Message',
    ],

    'messages' => [
        'order_has_been_shipping' => '
Success delivered,
Happy to serve you',
        'notify_customer_by_payment_confirmation' => '
Dear *:CUSTOMER_NAME*
Your payment has been confirmed for order :ORDER_ID
Your order processing now, we will keep you in touch
',
        'notify_unpaid_order_message' => "Reminder ..\nPayment deadline for order number (:ORDERID) is soon .\nPlease pay before\n:DATE\n:TIME",
        'as_your_request' => '1- At Your Request',
        'not_paid' => '1- Not Paid.',
        'unpaid_order_canceled_message' => "Dear Customer *:CUSTOMER_NAME*,\n Sorry, Your Order #:ORDERID has been automatically cancelled \n\n Cancelled Reasons:\n :REASON . \n\n Customer Service (WhatsApp) \n Wa.me/065433",
        'notify_customer_by_new_order_message_payment_link' => '
Please fill this form after transfer:
    :PAYMENT_URL

',
        'notify_customer_by_new_order_message' => '
Dear *:CUSTOMER_NAME*,
Thank you for ordering form *Almesbar Shop*
Order: *:ORDER_ID*
Amount: *:AMOUNT*

Payment via transfer to our account in Rajhi bank named: Bait Almesbar For Trading



Deadline for Payment
    *:DEADLINE_DATE*
    *:DEADLINE_TIME*
',

        // From Rajhi bank:
        // *122608010398991*

        // From other banks (IBAN):
        // *SA7280000122608010398991*
        'send_from_rajhi' => 'From Rajhi bank:',
        'send_from_other_banks_via_iban' => 'From other banks (IBAN):',
        'invalid_order_payment' => '
Dear *:CUSTOMER_NAME*
Unfortunately, Your Payment For Order #:ORDER_ID is Invalid
For more information, you can contact customer service via the following link',

        'order_shipped_with_shipping_method' => '
Dear *:CUSTOMER_NAME*
Your Order #:ORDER_ID has been shipped via :SHIPPING_METHOD
Tracking Number :TRACKING_NUMBER
Tracking Link
:TRACKING_URL
',

        'order_shipped_with_deivery_man' => '
Dear *:CUSTOMER_NAME*
Your Order #:ORDER_ID has been shipped with delivery man
:DELIVERY_MAN
:DELIVERY_MAN_NUMBER
Receipt code :CODE
',

        'order_shipping_confirmation' => '
Dear *:CUSTOMER_NAME*
Your Order #:ORDER_ID ready to pick up
Receipt code :CODE
',
        'success' => 'Success',
        'select_city' => 'Select City',
        'profile_information_updated' => 'Your Profile has been updated',
        'phone_number_has_been_changed' => 'Your phone number has been updated',
        'password_has_been_changed' => 'Your password has been updated',
        'confirm' => 'Confirm',
        'are_you_sure' => 'are you sure ? ',
        'yes' => 'Yes',
        'no' => 'No',
        'bank_account_has_been_created' => 'Your bank account has been updated',
        'invalid_otp' => 'invalid verification code',
        'invalid_activity' => 'This is invalid Activity',
        'invalid_activity_message' => 'Invalid Request',
        'order_payment_already_received' => 'We already received your payment confirmation request',
        'sorry' => 'Sorry',
        'order_has_been_canceled' => 'This order has been canceled, contact the support if you have problem',
    ],

    'footer' => [
        'about_us' => 'About Us',
        'contact' => 'Contact',
        'checkout' => 'Checkout',
        'my_account' => 'My Account',
        'subscribe' => 'Subscribe',
        'information' => 'Information',
        'cart' => 'Cart',
        'your_email' => 'Your Email',
        'join_news_letter' => 'Join Our Newsletter Now',
        'join_news_letter_bio' => "Subscribe To Get Latest Offer's.",
        'copyright_saved' => 'All Copyright rights © 2020  reserved | msbrshop',
        'address' => 'Address',
        'privacy' => 'Privacy & Policy',
        'terms' => 'Terms & Conditions ',
        'phone' => 'Phone',
        'email' => 'E-mail',
    ],
    'profile' => [
        'profile' => 'Control Panel',
        'logout' => 'Log Out',
        'new_customer' => 'New Customer',
        'exists_customer' => 'Have account',
        'login' => 'Login',
        'phone_number' => 'Mobile Number',
        'country' => 'Country',
        'or_create_new_account' => 'Or Create New Account ',
        'forget_password' => 'Forget Password ?',
        'password' => 'password',
        'new_password' => 'New Password',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'sign_up' => 'Sign Up',
        'account_number' => 'Account Number',
        'my_info' => 'My Info',
        'orders' => 'Orders',
        'mobile' => 'Mobile',
        'addresses' => 'Addresses',
        'payments' => 'Payments',
        'email_address' => 'E-mail Address',
        'date' => 'Date',
        'order' => 'Order',
        'status' => 'Status',
        'amount' => 'Amount',
        'operations' => 'Operations',
        'detail' => 'Detail',
        'old_password' => 'Old Password',
        'password_confirmation' => 'password Confirmation',
        'name' => 'Name',
        'state' => 'State',
        'city' => 'City',
        'address' => 'Description',
        'zip' => 'Zip',
        'edit' => 'Edit',
        'already_have_account' => 'Already Have Account ?',
        'reset_password' => 'Reset Password',
        'confirm_otp' => 'Enter Verification Code',
        'street_name' => 'Street',
        'area' => 'Area',
        'tel' => 'Telephone',
    ],

    'cart' => [
        'i_agree_to' => 'I agree to the ',
        'terms_and_conditions' => 'terms and conditions',
        'cart_empty' => 'Cart is empty',
        'shopping_here' => 'Shopping Here',
        'checkout' => 'Checkout',
        'image' => 'Image',
        'product_name' => 'Product Name',
        'price' => 'Price',
        'quantity' => 'Quantity',
        'total' => 'Total',
        'login_to_checkout' => 'Checkout',
        'inc_vat' => 'Inc Vat',
        'shipping_address' => 'Shipping Address',
        'shipping_method' => 'Shipping Method',
        'bank_transfer' => 'Bank Transfer',
        'mada' => 'Mada',
        'visa' => 'Visa',
        'sdad' => 'Sadad',
        'confirm_order' => 'Confirm Order',
        'you_order_processed' => 'You will receive payment instructions via whatsapp to',
        'select_address' => 'Select You Shipping Address',
        'create_address' => 'Create Address',
        'free' => 'Free',
        'shipping_weight' => 'Shipping Weight',
        'shipping_total' => 'Shipping Total',
        'net' => 'Net Amount',
        'create_shipping_address' => 'Create Shipping Address',
        'select_shipping_address' => 'Select Shipping Address',
        'back_to_cart' => 'Back To Cart',
    ],

    'order' => [
        'statuses' => [
            'issued' => 'Issued',
            'pending' => 'Pending',
            'paid' => 'Paid',
            'in_progress' => 'In Progress',
            'ready_for_shipping' => 'Ready For Shipping',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'canceled' => 'Canceled',
            'returned' => 'Returned',
        ],
        'pdf' => 'Invoice',

        'id' => 'Id',
        'amount' => 'Amount',
        'payment_method' => 'Payment Method',
        'tracking_number' => 'Tracking number',
        'shipping_method' => 'Shipping method',
        'created_at' => 'Created at',
        'status' => 'Status',

        'no_order' => 'No Orders',
        'cancel' => 'Cancel Order',
        'sales_invoice' => 'Sales Invoice',
        'vatId' => 'VAT NO.',
        'taxId' => 'CR NO.',
        'phone' => 'Phone',
        'branch' => 'Branch',
        'online_sales' => 'Online Sales',
        'shipping_address' => 'Shipping Address',
        'total' => 'Total',
        'tax' => 'VAT',
        'shipping' => 'Shipping',
        'net' => 'NET',
        'paid' => 'Paid',
        'happy_to_serv_you' => 'Thank You',
        'terms_privacy' => 'Terms & Conditions',
        'terms_list' => [
            '* Item is in original packing condition.',
            '* Return is within 3 days and exchange is within 7 days of date of purchase.',
        ],
        'our_address_state' => 'Gassim',
        'draft' => 'Quotation Invoice',
        'thanks_for_order' => 'Thank You',
        'created' => 'Success',
        'instructions_for_payment' => 'You will receive a WhatsApp message including bank account & payment confirmation link to mobile number',
        'payment_confirmation' => 'Payment Confirmation',
        'order' => 'Order',
        'remmning_time_to_auto_cancel_order' => 'Payment Deadline',
        'transmitter_name' => 'Transmitter Name',
        'to_blank' => 'Beneficiary Bank',
        'payment_confirmed' => 'we have received your payment confirmation request',
        'payment_confirmed_message' => 'Once we have received your payment we will confirm your order. we will keep you in touch',
    ],
    'products' => [
        'warranty_subscription' => 'Warranty',
        'all_of_them' => 'All',
        'inc' => 'Including VAT',
        'subcategories' => 'Sub Categories',
        'sorting_via' => 'Sorted By',
        'tags' => 'For more accurate results.. click on the tag',
        'show_more' => 'show more',
        'in' => 'In',
        'products' => 'Products',
        'products_count' => 'Products Count',
        'all_products_count' => 'All Products',
        'product' => 'Product',
        'show_all' => 'All',
        'add_to_cart' => 'Add To Cart',
        'remove_to_cart' => 'Remove From Cart',
        'add_to_favourite' => 'Add To Favourite',
        'remove_from_favourite' => 'Remove From Favourite',
        'product_specifications' => 'Product Specifications',
        'out_of_stock' => 'Out Of Stock',
        'sar' => 'SR',
        'kg' => 'K.G',
        'name' => 'Product Name',
        'quantity' => 'QTY',
        'price' => 'Price',
        'total' => 'Total',
        'model' => 'Model',
        'description' => 'Description',
        'filters' => 'Search Filters',
        'apply' => 'Apply',
        'reset' => 'Reset',
        'filters_for_search' => 'Search Filters..',
        'barcode' => 'Barcode',
        'related_products' => 'Related Products',
        'sorting_low_price' => 'lowest price',
        'sorting_high_price' => 'highest price',
        'sorting_lastest' => 'latest',
        'sorting_oldest' => 'oldest',
        'sorting_only_available' => 'Available Only',

        'more_than' => 'More Than',
        'all' => 'All',
        'prices' => 'Prices',
        'category' => 'Category',
        'remove_all' => 'Clear All',
        'sorting_most_sellers' => 'Most Sellers',
        'special_offer' => 'Enjoy a Special Offer',
        'time_days' => 'Days',
        'time_hrs' => 'HRS',
        'time_mins' => 'MINS',
        'time_secs' => 'SECS',
        'sale_for' => 'Sale for',
        'agent_warrnaty' => 'Agent Warrnaty',
        'new_arrival' => 'New Arrival',
    ],
    'content' => [
        'about_us_content' => '<h4 class="content__title">About Use</h4>Bait Almesbar For Trading
        CR: 1132002748
        VAT No.: 301032266600003
        Almesbar Shop is a website specialized in selling gadgets and electronic devices such as computers, smart watches, phones, as well as some digital products such as Gift Cards, Operation systems, and others, as our site is proud of being a leading website in this field at a very competitive price compared to other stores in Saudi Arabia market.
        Almesbar Shop, since its inception, has gained the confidence of all users thanks to its excellence in providing a store with the latest technological and programming standards to facilitate displaying and accessing products, as well as by providing high-quality products while ensuring the buyer\'s right in case he wants to return or replace the product.
        We could not reach this success except with the support and confidence of our customers, and because they always have a constant desire for excellence, we always strive to develop the store with everything new and keep pace with the aspirations of our customers.<h4 class="content__title">Our Values</h4>
        Believing in making the online shopping experience a unique and distinctive one, we made Almesbar Shop in a simplified way, where the buyer will find in our platform the diversity of products and the ease in selecting and searching for them, and he will find a technical support team ready throughout the day and seven days a week to answer the questions of our distinguished visitors and help in case There is problems.
        Almesbar Shop staff has made every effort to make our shopping process enjoyable, easy and fast, and provides everything the customer needs while taking care of the shipping process if you are inside the city of Riyadh, in addition to that we offer weekly offers and discounts for all our loyal customers.<h4 class="content__title">Our strategy</h4>
        Almesbar Shop, since its beginning, seeks to pay attention to the quality aspect, as we do not offer any products that do not meet the quality standards imposed by the Kingdom of Saudi Arabia or any products that have any harm to users and their safety, as we always urge our suppliers to respect safety standards, whether in manufacturing, by not using materials Dangerous and carcinogenic to use. We do not offer products that are not safe for use, especially children.
        Among our goals is also to make Almesbar Shop a title of excellence, quality, and ease of use, so we are keen to make purchasing from our store a unique experience by relying on a user friendly interface that allows our visitors to move between the sections easily, in addition to that our platform is Responsive which is viewable to all Devices, whether desktop devices or mobiles and tablets, and what distinguishes our platform also is continuous communication with our visitors, whether through the Contact Us page, which everyone can use to reach us or through our pages on social media, which makes our site in line with the interests of our valued visitors, which is also called User Feedback.        <h4 class="content__title">How can I buy from Almesbar Shop ?</h4>
        Please visit the site in Arabic or English and choose the appropriate section that you want to buy from or directly the products you want to buy in the main page by clicking on the product and reading all its details and then clicking on Add to Cart and complete the payment process with your preferred method or choose between "Continue Shopping" If you want to add more products or finalize the purchase\', if you have added all the products you want.
        You can create a new membership if you are a new user or log in to the Almesbar Shop using your email and password.',
        'terms_and_conditions_content' => "<h4 class='content__title'>Terms And Conditions</h4>By using the store, you implicitly agree to the terms and conditions shown on this page. These terms may change from time to time, so be sure to visit the page frequently.
        All the terms and conditions set forth on this page do not contradict any of the laws in force locally or laws that violate privacy, intellectual property and common property applicable internationally. You can learn more about this by visiting the \"Privacy Policy\" page.
        terms that you must adhere to:<h4 class='content__title'>(1) Introduce</h4>
        Welcome to Almesbar Shop. The following are the terms and conditions for your use and access to the pages of the website:
        Your entry and use of our site are your agreement to accept the terms and conditions of this agreement, which includes all the details below, and it is a confirmation of your commitment to respond to the content of this agreement for the Almesbar Shop hereinafter referred to as “we” and also referred to as “the site”, in the rest of the terms of the “terms and conditions” \"And this agreement is valid as soon as you access the site.
        <h4 class='content__title'>(2) Membership</h4>
        1. No person has the right to use the site if his membership is canceled from the Almesbar Shop.
        2. In the event that any user registers as a commercial establishment, his commercial establishment shall be bound by all the terms and conditions stated in this agreement.
        3. You must comply with all applicable laws to regulate Internet commerce.
        4. No member or institution has the right to open two accounts simultaneously for any reason, and the site’s management has the right to freeze the two accounts or cancel one of them with a commitment to liquidate all financial obligations related to the account before closing it.
        <h4 class='content__title'>(3) Registration obligations</h4>
        Immediately upon submitting the application to register for membership on the site, you will be required to disclose specific information and choose an email and password to be used when entering
        the site. When you activate your account, you will be considered a member of the site and thus you have agreed to:
        1. You are responsible for maintaining the confidentiality of your account information and confidential password. By doing so, you agree to inform Almesbar Shop immediately of any unauthorized use of your password or account or any other breach of your confidential information.
        2. Almesbar Shop will not be responsible in any way for any loss that may happen to you directly or indirectly, morally or financially as a result of revealing the username or login password information.
        3. By registering your membership on the ALMESBAR SHOP website, you are considered the exclusive and sole responsible for any direct or indirect losses that may be caused to the Almesbar Shop as a result of any illegal or authorized use of your account by you or by any other person who obtained the keys to access your account on the site, whether by authorization from you or Without authorization.
        4. You agree to provide true, correct, updated and complete information about yourself as required in the registration form for Almesbar Shop.
        5. Our store does not offer exchanges or returns for digital products.
        6. Almesbar Shop is committed to treating your personal information and your contact addresses with strict confidentiality.
        7. You will be obligated to keep the registration data and always update it to keep it true, correct, current and complete, and if you disclose information that is not true, incorrect, not current, incomplete, or contrary to what is stated in the terms and conditions agreement, then Almesbar Shop has the full right to stop Or limiting or canceling your membership and your account on the site, without prejudice to the rights of the Almesbar Shop and its other legitimate means to recover its rights.
        8. Almesbar Shop has the absolute will, and at any time, to conduct any investigations it deems necessary (directly or through a third party) and asks you to disclose additional information or documents of whatever size to prove your identity and ownership of your financial instruments.
        9. In the event that the applicant for the registration application represents a commercial establishment, he must provide all the required information and documents that include your commercial license, other documents for the institution and documents that show the responsibility of any person acting on your behalf.
        10. In the event of non-compliance with any of the above, the management of Almesbar Shop has the right to suspend or cancel your membership and block you from the site. We also reserve the right to cancel any unverified and unverified accounts, operations or accounts that have been inactive for a long time.
        <h4 class='content__title'>(4) Amendment to terms and conditions:</h4>
        1. You know and agree that Almesbar Shop will make any amendment to the terms and conditions agreement, according to which your obligations will double or your rights diminish according to any amendments that may be made to the terms and conditions agreement without the need to inform you.
        2. You agree that Almesbar Shop has the absolute power and without liability to make basic or subsidiary amendments to this agreement without requiring additional approval from your side, at any time and with immediate effect, without being obligated to inform you, and you can always know the modifications through reference for this page.
        3. You agree that the Almesbar Shop is a technical electronic website on the Internet that enables the purchase of electronic and digital products.
        4. Membership on the site is free.
        5. There is no shipping for digital products, unless you buy the cartoon products, it requires shipping.
        6. All fees are calculated in Saudi riyals. You must pay all fees due on your operations on the site in addition to any other expenses added by the Almesbar Shop, provided that payment is made by the approved means.
        7. In the event that you are not obligated to pay the fees or expenses calculated on your operations on the site and the period exceeds 3 working days, the Almesbar Shop, without any legal liability, reserves the right to cancel your purchase order.
         <h4 class='content__title'>(5) Payment for purchases</h4>
        1. The payment system in the Almesbar Shop can be done entirely online through the payment options available on the Almesbar Shop, which are the payment by credit cards or through any payment method provided by the Almesbar Shop from time to time.
        2. Almesbar Shop has all the powers to choose the offered product and the appropriate price for each product as the site deems appropriate.
        3. The role of Almesbar Shop ends as soon as you receive your purchases. In the event of an exchange or return, you must address the store directly.
         <h4 class='content__title'>(6) Your personal information</h4>
        1. You agree that the Almesbar Shop has the right to dispose of your information within what is specified on this page and in the privacy policy page, permanently and irrevocably, through registration, purchase, or use of forms designated for communication and registration, or via any electronic message or Any of the communication channels available on the site. This is in order to operate and promote the site in accordance with the disclaimer agreement and privacy statement.
        2. You are the only one responsible for the information that you send or publish, and the role of Almesbar Shop is limited to allowing you to display this information on the website's pages and through its advertising channels.
         <h4 class='content__title'>(7) Confidentiality of data</h4>
        1. Almesbar Shop takes measures (tangible, organizational, and technological) to protect against unauthorized access to your personal identity information, and to store it. Knowing that the Internet is not a secure medium,
        2. ALMESBAR SHOP has no control over the actions of any third party, such as other Internet pages linked to this site, or third parties that claim to represent you and others.
        3. You know and agree that the Almesbar Shop may use your information that you provided it with, in order to provide services to you in the Almesbar Shop, and to send you marketing messages, and that the privacy statement on this site controls the collection, processing, use and transfer of your personal identity information.
        (8) Revocation of the terms and conditions agreement:
        According to the terms and conditions agreement and according to the law, Almesbar Shop may resort to a temporary or permanent suspension or withdrawal and cancellation of your membership and limiting or canceling your access to the site without prejudice to its other rights and its legitimate means to recover your rights in the event:
        1. If you violate the terms and conditions agreement.
        2. If the Almesbar Shop is not able to verify the validity of any of your information provided to it.
        3. If the Almesbar Shop decides that your activities may cause legal problems for you or other users and the Almesbar Shop.
        4. Almesbar Shop \"according to its evaluation\" may resort to re-activity of the suspended users, as the user who stopped his activity permanently or withdrew his membership, may not be able to register or try to register in the Almesbar Shop or use the site in any way, whatever the circumstances, until permitting Him to restart his activity in the store of Almesbar Shop. However, if you violated this agreement of terms and conditions, the store reserves the right to recover any amounts owed to the site on you, and any losses and damages caused to the Almesbar Shop. It also has the right to take legal measures and / or resort to the judiciary to file a criminal or commercial case against you as seen by the store Almesbar Shop.
         <h4 class='content__title'>(9) Store Rights</h4>
        Your or others' violation of this agreement does not oblige the Almesbar Shop to waive its right to take appropriate measures for such an act and for other similar acts of violation. Almesbar Shop does not guarantee that it will take measures against all violations of the terms and conditions agreement.
         <h4 class='content__title'>(10) Illegal contents</h4>
        As a registered member on the site, you are prohibited from advertising your phone numbers or e-mail through any part of the site, and this is considered a violation of the terms and conditions.
        You may only benefit from the site for legitimate and legal purposes. And you must abide by all applicable laws, as you are prohibited from using our products in any illegal and misleading actions, and you are also prohibited from using any part of our store, image or digital content, how it was on any other site without written approval from the store management.
         <h4 class='content__title'>(11) Feedback</h4>
        Our store encourages all members of the site to provide suggestions or feedback (feedback), and it will be presented on the site at any location determined by the site management.
         <h4 class='content__title'>(12) Cancellation of access / membership</h4>
        Without prejudice to its other rights and its legitimate means to recover its rights, the Almesbar Shop can stop or cancel your membership or your access to the site at any time without warning and for any reason, and without limitation, and it can also cancel this agreement of terms and conditions.
         <h4 class='content__title'>(13) Warranty</h4>
        Our store provides perfect digital and cartoon products and therefore it is difficult for us to provide guarantees on all the products that we offer or guarantee their replacement or return, all our digital products are not subject to the warranty or return policy, and if we add products with a guarantee in the future, we will refer to that in the terms or in Product Description.
         <h4 class='content__title'>(14) Defining Responsibilities</h4>
        As permitted by law, our store, its employees, directors, agents, subsidiaries and suppliers will not be responsible for any direct or indirect loss or malfunctions arising from your use of the site. If you are not satisfied with the site or any of its contents, the solution is to not continue to use it, in addition to that, you agree that any unauthorized use of the site and its services, due to your negligence, will cause harm to the Almesbar Shop, and therefore the site will have to resort to the terms and conditions.
         <h4 class='content__title'>(15) Safety</h4>
        You agree to your safe use of Almesbar Shop while preserving the security of its directors, employees, agents and suppliers and protecting them from any damage that may be caused to them as a result of claims, losses, breakdowns, costs and expenses that occur due to your violation of the terms and conditions agreement or your breach of any law, amendments or infringement of the rights of third parties.
         <h4 class='content__title'>(16) Relationship and Notices</h4>
        None of the terms of the terms and conditions agreement includes an indication of a partnership between you and the Almesbar Shop. You do not have the authority to bind the Almesbar Shop in any way, and any notices that you want to send to the Almesbar Shop must be sent by e-mail, provided that the Almesbar Shop responds to the email. You know and agree that any notices sent to you from the Almesbar Shop will be announced on the website or by the e-mail you provided to us during the registration process.
         <h4 class='content__title'>(17) Transfer of rights and obligations</h4>
        You here grant Almesbar Shop the right to transfer part or all of its rights, benefits, obligations and responsibilities, to other parties you work with, without the need to refer to you, according to the provisions of the terms and conditions agreement, and Almesbar Shop is obligated to notify you of such transfers if they take place, as well as to publish on the site.
        Almesbar Shop reserves the right to report any activity if it suspects that it violates any of the applicable laws, to the relevant law enforcement officials, regulatory authorities, or others. And in order to cooperate with government requests to protect ALMESBAR SHOP and its clients or to ensure the integrity of the business and systems of ALMESBAR SHOP, it may enter and disclose any information it deems necessary or appropriate, including but not limited to its account data, contact information, IP address, data traffic information and date Use and posted content. Both the Almesbar Shop and the user must protect customer data according to their respective policies and the laws in force in the selected country.
         <h4 class='content__title'>(18) General information</h4>
        If any paragraph contained in this terms and conditions agreement is invalid or canceled, or for any reason it is no longer in force, then such paragraph does not nullify the validity of the remaining paragraphs contained in the agreement. This agreement (which is amended from time to time according to its terms) sets out all the outlines of understanding and agreement between you and the Almesbar Shop, taking into account the following:
        - Any person who is not a party to this agreement has no right to impose any terms or conditions in it.
        - If the terms and conditions agreement is translated into any other language, whether on the site or in other ways, the Arabic text for it will prevail.
         <h4 class='content__title'>(19) Governing Law and Legislation</h4>
        This agreement of terms and conditions is governed and drafted according to the laws regulating the safe use of European digital data and does not contradict with local or international laws in force in this field.
        ",
        'privacy_content' => "<h4 class='content__title'>Privacy Policy</h4>We appreciate your concerns about the privacy of your data on the Internet and on the website www.msbrshop.com This policy has been prepared to assist you in understanding the nature of the data that we collect about you when you visit our website and how we deal with this personal data.
        By using Almesbar Shop, you agree to all the terms of this page and the \"Terms and Conditions\" page shown on the site, and you are obligated to accept these terms and conditions applicable to you from your entry to the site until you leave it.
        The site administration is obligated, in accordance with the data protection laws approved by international organizations, not to disclose any personal information about the user to our website such as name, address, phone number or e-mail address and others that you provide either when commenting on our site or when you contact to us.
        Almesbar Shop is obligated not to exchange, trade any of this information or sell it to any other party as long as it is within the limits of the possible site management capabilities, and access to the information will only be allowed to qualified and professional people who supervise the Almesbar Shop website.
        <h4 class='content__title'>Responsibilaty</h4>The user / visitor to our website Almesbar Shop acknowledges that he is the exclusive and sole responsible for the nature of the use of the site and the integrity of the data and information that he enters or provides to the site, and the Almesbar Shop administration disclaims all responsibility for any damage, leakage, imbalance, or expenses incurred by the user or exposed to it as a result of using the Almesbar Shop.
        <h4 class='content__title'>Browsing</h4>Almesbar Shop website collects data about you while you are browsing the site as your cookies or any other information, and due to our use of Google Analytics as a third party, a few information about you that Google identifies may be collected, you can check what information on this page - Privacy Policy.
        <h4 class='content__title'>Internet Protocol (IP) address</h4>Anytime you visit any website including Almesbar Shop website, the host server will record your Internet Protocol (IP) address, the date and time of the visit, the type of Internet browser you are using, and the URL of any website that references you to this site on the web.
        <h4 class='content__title'>External links and ads</h4>Our site may include links to other websites on the Internet. Or ads from advertising companies such as Google AdSense and we are not responsible for the methods of data collection by those sites, you can see the privacy policies and contents of those sites when accessing them.
        We may use third-party advertising companies to serve ads when you visit our site. These companies have the right to use information about your visits to our website (except for the name, address, email address or phone number), in order to provide advertisements about the goods and services that interest you.
        We also may use Affiliate Links for some companies to market products or services to you, knowing that we do not have any interference or responsibility in the data that you enter in the pages of these products or in the information they collect about you, and you can always Learn about the policies of those sites by visiting their privacy policy page on their website.
        <h4 class='content__title'>Disclosure of information</h4>We will at all times maintain the privacy and confidentiality of all personal data that we obtain. This information will only be disclosed if it is required by any law or court memo, and only when we believe in good faith that such action will be required or desirable to comply with the law, or to defend or protect the property rights of this site or its beneficiaries.
        <h4 class='content__title'>Fair use of our services</h4>The visitor assumes all responsibility for all his data, which he uploads and publishes through our website.
        The visitor shall not attempt to access, piracy, or amend any information about him or the administration that has no authority to access it. In the event that this is done, the site and its administration shall have the full right to pursue judicially.
        The visitor shall abide by all conditions of use, and not to use the Almesbar Shop website or anything that falls within our site for anything that violates Islamic law in any way, or for unlawful purposes, for example but not limited to, such as: piracy, publication, and distribution of copied materials or programs , Deception, forgery, fraud, threat, or inconvenience to any person, company, group, posting viruses or spyware, or placing links to sites that contain such violations ..
        Violating intellectual property rights or defaming the reputation of a person, institution, company or intentionally publishing any information that causes harm to a company, person, country or group and not to put piracy materials and stolen programs and all that contradicts the norms and laws are prohibited, and the subscriber is fully responsible for everything he provides via His account on the site.
        <h4 class='content__title'>Amendments to items</h4>We reserve the right to amend the terms and conditions of the Privacy Policy if necessary. The amendments will be implemented here or on the terms and
        conditions page and you may or may not be notified continuously with the data we have obtained, how we will use it and who we will supply it with.
        <h4 class='content__title'>Contact us</h4>You can contact us when needed through the phone number or the contact us page or send to our email.
         <h4 class='content__title'>finally</h4>Your concerns about the confidentiality and privacy of the data are very important for us. We hope that all your concerns will be cleared and all your data paths clarified through this policy.
        In the event that you are not satisfied with anything on this page or in the terms and conditions page, please stop using our site.",
    ],
];
