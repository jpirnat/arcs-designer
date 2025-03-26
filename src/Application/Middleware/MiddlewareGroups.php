<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Middleware;

final readonly class MiddlewareGroups
{
    /**
     * The default middleware group for json routes.
     *
     * @var string[] $JSON
     */
    public const array JSON = [
        JsonErrorMiddleware::class,
    ];

    /**
     * The default middleware group for index routes.
     *
     * @var string[] $HTML
     */
    public const array HTML = [
        HtmlErrorMiddleware::class,
    ];

    /**
     * The /error page should use this empty group to minimize the risk of an
     * infinite redirect loop.
     *
     * @var string[] $ERROR
     */
    public const array ERROR = [];
}
