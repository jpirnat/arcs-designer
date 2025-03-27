<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

use Jp\ArcsDesigner\Application\Controllers\IndexController;
use Jp\ArcsDesigner\Application\Middleware\MiddlewareGroups;
use Jp\ArcsDesigner\Presentation\IndexView;

// Common route parameter definitions.
$id = '{id:[-\w]+}';

// Route definitions.
return [
    ['GET', '/cards', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'cards',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['GET', '/data/cards', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\CardsController::class,
        'controllerMethod' => 'setData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\CardsView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['GET', '/cards/add', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'card',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['GET', '/data/cards/add', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\CardController::class,
        'controllerMethod' => 'setAddData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\CardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['POST', '/cards/add', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\EditCardController::class,
        'controllerMethod' => 'addCard',
        'viewClass' => \Jp\ArcsDesigner\Presentation\EditCardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['GET', "/cards/$id", [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'card',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['GET', "/data/cards/$id", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\CardController::class,
        'controllerMethod' => 'setEditData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\CardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['POST', "/cards/$id", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\EditCardController::class,
        'controllerMethod' => 'editCard',
        'viewClass' => \Jp\ArcsDesigner\Presentation\EditCardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],

    ['GET', '/error', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'error',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],
];
