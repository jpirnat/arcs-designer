<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Middleware;

use Jp\ArcsDesigner\Application\CookieNames;
use Jp\ArcsDesigner\Application\Environments;
use Jp\ArcsDesigner\Application\Services\LoginService;
use Jp\ArcsDesigner\Application\SessionVariables;
use Jp\ArcsDesigner\Domain\Logins\IncorrectLoginTokenException;
use Jp\ArcsDesigner\Domain\Logins\InvalidLoginSelectorException;
use DateTime;
use DateTimeInterface;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class LoginMiddleware implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session,
        private LoginService $loginService,
        private string $environment,
    ) {}

    /**
     * If the user isn't logged in and the request contains a login token,
     * attempt to log the user in.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ) : ResponseInterface {
        $loginToken = null;
        $expireTheLoginCookies = false;

        $isLoggedIn = $this->session->has(SessionVariables::USER_ID);
        $selector = $request->getCookieParams()[CookieNames::LOGIN_SELECTOR] ?? '';
        $token = $request->getCookieParams()[CookieNames::LOGIN_TOKEN] ?? '';

        if (!$isLoggedIn && $selector && $token) {
            try {
                $loginToken = $this->loginService->loginViaToken(
                    $selector,
                    $token,
                );
            } catch (InvalidLoginSelectorException | IncorrectLoginTokenException) {
                // The credentials are invalid or expired. Expire the cookies.
                $expireTheLoginCookies = true;
            }
        }

        $response = $handler->handle($request);

        if ($loginToken) {
            // Extend the login cookies.
            $expires = $loginToken->expires->format(DateTimeInterface::COOKIE);

            $setCookie = SetCookie::create(CookieNames::LOGIN_SELECTOR);
            $setCookie = $setCookie->withValue($selector);
            $setCookie = $setCookie->withPath('/');
            $setCookie = $setCookie->withExpires($expires);
            $setCookie = $setCookie->withSecure(
                $this->environment === Environments::PRODUCTION
            );
            $setCookie = $setCookie->withHttpOnly();
            $response = FigResponseCookies::set($response, $setCookie);

            $setCookie = SetCookie::create(CookieNames::LOGIN_TOKEN);
            $setCookie = $setCookie->withValue($token);
            $setCookie = $setCookie->withPath('/');
            $setCookie = $setCookie->withExpires($expires);
            $setCookie = $setCookie->withSecure(
                $this->environment === Environments::PRODUCTION
            );
            $setCookie = $setCookie->withHttpOnly();
            $response = FigResponseCookies::set($response, $setCookie);
        }

        if ($expireTheLoginCookies) {
            // Expire the login cookies.
            $setCookie = SetCookie::create(CookieNames::LOGIN_SELECTOR);
            $setCookie = $setCookie->withValue('');
            $setCookie = $setCookie->withPath('/');
            $setCookie = $setCookie->withSecure(
                $this->environment === Environments::PRODUCTION
            );
            $setCookie = $setCookie->withHttpOnly();
            $response = FigResponseCookies::set($response, $setCookie->expire());

            $setCookie = SetCookie::create(CookieNames::LOGIN_TOKEN);
            $setCookie = $setCookie->withValue('');
            $setCookie = $setCookie->withPath('/');
            $setCookie = $setCookie->withSecure(
                $this->environment === Environments::PRODUCTION
            );
            $setCookie = $setCookie->withHttpOnly();
            $response = FigResponseCookies::set($response, $setCookie->expire());
        } else {
            // Regenerate the session cookie.
            $sessionName = $this->session->getName();
            $sessionId = $this->session->getId();
            $expires = new DateTime('+20 minutes')->format(DateTimeInterface::COOKIE);

            $setCookie = SetCookie::create($sessionName);
            $setCookie = $setCookie->withValue($sessionId);
            $setCookie = $setCookie->withPath('/');
            $setCookie = $setCookie->withExpires($expires);
            $setCookie = $setCookie->withSecure(
                $this->environment === Environments::PRODUCTION
            );
            $setCookie = $setCookie->withHttpOnly();
            $response = FigResponseCookies::set($response, $setCookie);
        }

        return $response;
    }
}
