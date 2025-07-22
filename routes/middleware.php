<?php

use Spatie\Permission\Middleware\PermissionMiddleware as MiddlewarePermissionMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;

return [
    'web' => [
        // Kosongkan untuk test
    ],
    'api' => [],
    'routeMiddleware' => [
        'permission' => MiddlewarePermissionMiddleware::class,
    ],
];
