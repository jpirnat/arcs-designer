<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Middleware;

final readonly class MiddlewareGroups
{
    /**
     * The default middleware group to use for html routes
     * that require the user to be logged in.
     *
     * @var string[] $REQUIRE_LOGIN_HTML
     */
    public const array REQUIRE_LOGIN_HTML = [
        HtmlErrorMiddleware::class,
        LoginMiddleware::class,
        RequireLoginHtmlMiddleware::class,
    ];

    /**
     * The default middleware group to use for html routes
     * that require the user to be logged in.
     *
     * @var string[] $REQUIRE_LOGIN_JSON
     */
    public const array REQUIRE_LOGIN_JSON = [
        JsonErrorMiddleware::class,
        LoginMiddleware::class,
        RequireLoginJsonMiddleware::class,
    ];

    /**
     * The /error page should use this empty group to minimize the risk of an
     * infinite redirect loop.
     *
     * @var string[] $ERROR
     */
    public const array ERROR = [];
}
