<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],


    'whatsapp' => [
        'server' => env('WHATSAPP_SEVER', 'remote'),
        'base_url' => env('WHATSAPP_BASE_URL'),
        'token' => env('WHATSAPP_TOKEN'),
    ],

    'our_sms' => [
        'base_url' => env('OUR_SMS_BASE_URL', 'http://www.oursms.net/api/sendsms.php'),
        'username' => env('SMS_USER'),
        'password' => env('SMS_PASS'),
        'sender' => env('SMS_SEND_NAME'),
    ],
    'sms' => [
        'username' => env('SMS_USER'),
        'password' => env('SMS_PASS'),
        'send_name' => env('SMS_SEND_NAME'),
    ],
    'image_processing' => [
        'url' => env('IMAGE_PROCESSING_URL')
    ]

];
