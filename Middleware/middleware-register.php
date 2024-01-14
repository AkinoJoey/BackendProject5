<?php

return [
    'global' => [
        \Middleware\HttpLoggingMiddleware::class,
        \Middleware\SessionsSetupMiddleware::class,
        \Middleware\MiddlewareA::class,
        \Middleware\MiddlewareB::class,
        \Middleware\MiddlewareC::class,
        \Middleware\CSRFMiddleware::class,
    ],
    'aliases' => [
        'auth' => \Middleware\AuthenticatedMiddleware::class,
        'guest' => \Middleware\GuestMiddleware::class,
        'signature' => \Middleware\SignatureValidationMiddleware::class,
        'verify' => \Middleware\EmailVerifiedMiddleware::class,
    ]
];
