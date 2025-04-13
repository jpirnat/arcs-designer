<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

use Jp\ArcsDesigner\Application\Controllers\IndexController;
use Jp\ArcsDesigner\Application\Middleware\MiddlewareGroups;
use Jp\ArcsDesigner\Presentation\IndexView;
use Jp\ArcsDesigner\Presentation\RedirectView;

// Common route parameter definitions.
$id = '{id:\d+}';

// Route definitions.
return [
    ['GET', '/', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => RedirectView::class,
        'viewMethod' => 'cards',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

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

    ['GET', '/sets', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'sets',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

    ['GET', '/data/sets', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\SetsController::class,
        'controllerMethod' => 'setData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\SetsView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['GET', '/sets/add', [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'set',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

    ['GET', '/data/sets/add', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\SetController::class,
        'controllerMethod' => 'setAddData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\SetView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['POST', '/sets/add', [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\EditSetController::class,
        'controllerMethod' => 'addSet',
        'viewClass' => \Jp\ArcsDesigner\Presentation\EditSetView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['GET', "/sets/$id", [
        'controllerClass' => IndexController::class,
        'controllerMethod' => 'index',
        'viewClass' => IndexView::class,
        'viewMethod' => 'set',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_HTML,
    ]],

    ['GET', "/data/sets/$id", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\SetController::class,
        'controllerMethod' => 'setEditData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\SetView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['POST', "/sets/$id", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\EditSetController::class,
        'controllerMethod' => 'editSet',
        'viewClass' => \Jp\ArcsDesigner\Presentation\EditSetView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
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

    ['POST', "/cards/set-as-current", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\SetAsCurrentController::class,
        'controllerMethod' => 'setData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\SetAsCurrentView::class,
        'viewMethod' => 'getData',
        'middlewareClasses' => MiddlewareGroups::REQUIRE_LOGIN_JSON,
    ]],

    ['POST', "/cards/add-comment", [
        'controllerClass' => \Jp\ArcsDesigner\Application\Controllers\AddCommentController::class,
        'controllerMethod' => 'setData',
        'viewClass' => \Jp\ArcsDesigner\Presentation\AddCommentView::class,
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
