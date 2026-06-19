<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Webhook URL
    |--------------------------------------------------------------------------
    |
    | The URL to which the security alert POST request will be sent when
    | vulnerabilities are detected by composer audit.
    |
    */
    'webhook' => env('COMPOSER_AUDIT_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Basic Authentication
    |--------------------------------------------------------------------------
    |
    | Optional HTTP Basic Auth credentials for the webhook endpoint.
    | If either username or password is missing, the request will be
    | sent without authentication.
    |
    */
    'basic_auth' => [
        'username' => env('COMPOSER_AUDIT_WEBHOOK_USERNAME'),
        'password' => env('COMPOSER_AUDIT_WEBHOOK_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Site Name
    |--------------------------------------------------------------------------
    |
    | The name of the application included in the webhook payload.
    | Defaults to the APP_NAME environment variable.
    |
    */
    'site' => env('APP_NAME', 'Laravel Application'),

    /*
    |--------------------------------------------------------------------------
    | Project Path
    |--------------------------------------------------------------------------
    |
    | The path to the project root. This is included in the webhook payload
    | to identify which installation triggered the alert.
    |
    */
    'path' => base_path(),

];
