<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "log", "array", "failover", "roundrobin"
    |
    */

    'mailers' => [
        // Resend Mailer (custom switch handled in code path)
        'resend' => [
            'transport' => 'resend',
            'key' => env('RESEND_API_KEY'),
        ],
        'smtp' => [
            'transport' => 'smtp',
            // IMPORTANT: do not reuse MAIL_URL here because it may contain non-SMTP DSNs (e.g. gmail+api)
            'url' => env('MAIL_SMTP_URL'),
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        // SendGrid via Symfony Mailer (HTTP API)
        // Requires: composer require symfony/sendgrid-mailer
        // Usage: set MAIL_MAILER=sendgrid and SENDGRID_API_KEY in env.
        // Optionally, set MAIL_URL=sendgrid+api://<API_KEY>@default to override.
        'sendgrid' => [
            'transport' => 'sendgrid',
            'url' => env('MAIL_URL'),
            // Some environments rely on DSN only; still expose key for factories that support it
            'key' => env('SENDGRID_API_KEY'),
            'timeout' => env('MAIL_TIMEOUT', 5),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => null,
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'mailgun' => [
            'transport' => 'mailgun',
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        // Gmail API (OAuth2) via symfony/google-mailer
        // Use DSN in MAIL_URL, e.g.:
        // MAIL_MAILER=gmail
        // MAIL_URL=gmail+api://USER@gmail.com@default?client_id=XXX&client_secret=YYY&refresh_token=ZZZ
        'gmail' => [
            'transport' => 'gmail',
            // Use MAIL_URL for Gmail API DSN: gmail+api://CLIENT_ID:CLIENT_SECRET@default?refresh_token=...
            'url' => env('MAIL_URL'),
            // Optional explicit params (when not using DSN)
            'user' => env('GOOGLE_GMAIL_USER'),
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
            'access_token' => env('GOOGLE_ACCESS_TOKEN'), // optional pre-fetched token
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
