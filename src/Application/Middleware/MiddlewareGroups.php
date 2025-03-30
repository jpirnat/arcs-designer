<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Middleware;

final readonly class MiddlewareGroups
{
    /**
     * The default middleware group to use for html routes
     * that don't require the user to be logged in.
     *
     * @var string[]
     */
    public const array HTML = [
        HtmlErrorMiddleware::class,
    ];

    /**
     * The default middleware group to use for json routes
     * that don't require the user to be logged in.
     *
     * @var string[]
     */
    public const array JSON = [
        JsonErrorMiddleware::class,
    ];

    /**
     * The default middleware group to use for html routes
     * that require the user to be logged in.
     *
     * @var string[]
     */
    public const array REQUIRE_LOGIN_HTML = [
        HtmlErrorMiddleware::class,
        LoginMiddleware::class,
        RequireLoginHtmlMiddleware::class,
    ];

    /**
     * The default middleware group to use for json routes
     * that require the user to be logged in.
     *
     * @var string[]
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
     * @var string[]
     */
    public const array ERROR = [];
}
