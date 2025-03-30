<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Middleware;

use Jp\ArcsDesigner\Application\SessionVariables;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class RequireLoginJsonMiddleware implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {}

    /**
     * Require the user to be logged in to access this page. If the user isn't
     * logged in, give them an error message.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ) : ResponseInterface {
        if (!$this->session->has(SessionVariables::USER_ID)) {
            return new JsonResponse([
                'errors' => [
                    'message' => 'You need to be logged in to access this page.',
                ],
            ]);
        }

        return $handler->handle($request);
    }
}
