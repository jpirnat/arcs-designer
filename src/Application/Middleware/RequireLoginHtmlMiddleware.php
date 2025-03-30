<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Middleware;

use Jp\ArcsDesigner\Application\SessionVariables;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class RequireLoginHtmlMiddleware implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {}

    /**
     * Require the user to be logged in to access this page. If the user isn't
     * logged in, redirect them to the login page.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ) : ResponseInterface {
        if (!$this->session->has(SessionVariables::USER_ID)) {
            return new RedirectResponse('/login');
        }

        return $handler->handle($request);
    }
}
