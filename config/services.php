
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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
'razorpay' => [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
    'account_number' => env('RAZORPAY_ACCOUNT_NUMBER', '2323230041626905'),
],

'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    'temperature' => (float) env('OPENAI_TEMPERATURE', 0.7),
    'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 300),
    'presence_penalty' => (float) env('OPENAI_PRESENCE_PENALTY', 0.0),
    'frequency_penalty' => (float) env('OPENAI_FREQUENCY_PENALTY', 0.0),
],

'whatsapp' => [
    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),
    'access_token' => env('WHATSAPP_API_KEY'), // Use the API key as access token
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
    'verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
    'app_secret' => env('WHATSAPP_WEBHOOK_SECRET'),
    'webhook_url' => env('WHATSAPP_WEBHOOK_URL'),
    
    // Legacy support for existing keys
    'token' => env('WHATSAPP_API_KEY'),
],

];
