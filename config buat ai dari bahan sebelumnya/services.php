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
    | Groq API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Groq API integration used for AI chat responses.
    | Get your API key from: https://console.groq.com/keys
    |
    */

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'api_url' => env('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'),
        'model' => env('GROQ_MODEL', 'llama3-8b-8192'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cat API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for TheCatAPI.com used for fetching random cat images.
    | Get your API key from: https://thecatapi.com/signup
    | Note: API key is optional for basic usage
    |
    */

    'cat_api' => [
        'api_key' => env('CAT_API_KEY'),
        'api_url' => env('CAT_API_URL', 'https://api.thecatapi.com/v1/images/search'),
    ],

];