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
    | Cloudflare for SaaS (Custom Hostnames)
    |--------------------------------------------------------------------------
    | zone_id         — The Zone ID of your SaaS domain in Cloudflare.
    | api_token       — A scoped API token with "Custom Hostnames: Edit" permission.
    | fallback_origin — The fallback origin set in CF > SSL/TLS > Custom Hostnames
    |                   (e.g. app.yoursaas.com). Shown to tenants for their CNAME.
    */
    'cloudflare' => [
        'zone_id'         => env('CLOUDFLARE_ZONE_ID'),
        'api_token'       => env('CLOUDFLARE_API_TOKEN'),
        'fallback_origin' => env('CLOUDFLARE_FALLBACK_ORIGIN', 'app.bartarpyan.site'),
    ],

];
