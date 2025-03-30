<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Jp\ArcsDesigner\Application\CookieNames;
use Jp\ArcsDesigner\Application\Environments;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class LogoutView
{
    public function __construct(
        private SessionInterface $session,
        private string $environment,
    ) {}

    public function getData(): ResponseInterface
    {
        $response = new RedirectResponse('/login');

        // Expire the session cookie.
        $setCookie = SetCookie::create($this->session->getName());
        $setCookie = $setCookie->withValue('');
        $setCookie = $setCookie->withPath('/');
        $setCookie = $setCookie->withSecure(
            $this->environment === Environments::PRODUCTION
        );
        $setCookie = $setCookie->withHttpOnly();
        $response = FigResponseCookies::set($response, $setCookie->expire());

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
        return FigResponseCookies::set($response, $setCookie->expire());
    }
}
