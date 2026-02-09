<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Services
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'envoi de SMS. Provider disponibles:
    | - log (debug mode - logs instead of sending)
    | - twilio
    | - vonage
    | - infobip
    | - orange_tn
    |
    */

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'), // 'log' pour debug, 'twilio', 'vonage', etc.
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],

    'vonage' => [
        'key' => env('VONAGE_KEY'),
        'secret' => env('VONAGE_SECRET'),
        'from' => env('VONAGE_FROM', 'DeliveryApp'),
    ],

    'infobip' => [
        'key' => env('INFOBIP_KEY'),
        'base_url' => env('INFOBIP_BASE_URL'),
        'from' => env('INFOBIP_FROM', 'DeliveryApp'),
    ],

    'textflow' => [
        'api_key' => env('TEXTFLOW_API_KEY'),
    ],

    'orange_tn' => [
        'api_url' => env('ORANGE_TN_API_URL'),
        'api_key' => env('ORANGE_TN_API_KEY'),
        'sender_id' => env('ORANGE_TN_SENDER_ID'),
    ],

];
