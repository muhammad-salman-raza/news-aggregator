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

    'newsapi' => [
        'api_url' => env('NEWSAPI_URL', 'https://newsapi.org/v2/everything'),
        'api_key' => env('NEWSAPI_KEY', 'default_api_key'),
        'sources' => env('NEWSAPI_SOURCES', 'al-jazeera-english,abc-news'),
        'page_size' => env('NEWSAPI_PAGE_SIZE', '100'),
    ],

    'guardian' => [
        'api_url' => env('GUARDIAN_URL', 'https://newsapi.org/v2/everything'),
        'api_key' => env('GUARDIAN_KEY', 'default_api_key'),
        'page_size' => env('GUARDIAN_PAGE_SIZE', '100'),
    ],

    'nytimes' => [
        'api_url' => env('NYTIMES_URL', 'https://api.nytimes.com/svc/search/v2/articlesearch.json'),
        'api_key' => env('NYTIMES_KEY', 'your_nytimes_api_key'),
    ],

];
