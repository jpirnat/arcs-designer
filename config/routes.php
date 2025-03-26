<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

use Jp\ArcsDesigner\Application\Controllers\IndexController;
use Jp\ArcsDesigner\Application\Middleware\MiddlewareGroups;
use Jp\ArcsDesigner\Presentation\IndexView;

// Common route parameter definitions.

// Route definitions.
return [
    ['GET', '/error', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'error',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],
];
