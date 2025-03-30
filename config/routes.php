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
    ['GET', '/login', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'login',
        'middlewareClasses' => MiddlewareGroups::HTML,
    ]],

    ['POST', '/login', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\LoginController::class,
        'controllerMethod' => 'submit',
        'viewClass' => \Jp\ArcsDesigner\Presentation\LoginView::class,
        'viewMethod' => 'submit',
        'middlewareClasses' => MiddlewareGroups::JSON,
    ]],

    ['GET', '/logout', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\LogoutController::class,
        'controllerMethod' => 'setData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\LogoutView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::HTML,
    ]],

    ['GET', '/cards', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'cards',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

    ['GET', '/data/cards', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\CardsController::class,
        'controllerMethod' => 'setData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\CardsView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['GET', '/cards/add', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'card',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

    ['GET', '/data/cards/add', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\CardController::class,
        'controllerMethod' => 'setAddData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\CardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['POST', '/cards/add', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\EditCardController::class,
        'controllerMethod' => 'addCard',
        'viewClass' => \Jp\ArcsDesigner\Presentation\EditCardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['GET', "/cards/$id", [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'card',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

    ['GET', "/data/cards/$id", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\CardController::class,
        'controllerMethod' => 'setEditData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\CardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['POST', "/cards/$id", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\EditCardController::class,
        'controllerMethod' => 'editCard',
        'viewClass' => \Jp\ArcsDesigner\Presentation\EditCardView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['GET', '/error', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'error',
        'middlewareClasses' => MiddlewareGroups::ERROR,
    ]],
];
