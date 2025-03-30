<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use DateTimeInterface;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Jp\ArcsDesigner\Application\CookieNames;
use Jp\ArcsDesigner\Application\Environments;
use Jp\ArcsDesigner\Application\Models\LoginModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class LoginView
{
    public function __construct(
        private LoginModel $model,
        private string $environment,
    ) {}

    /**
     * Submit the login form.
     */
    public function submit(): ResponseInterface
    {
        if ($this->model->loginSelector
            && $this->model->loginToken
            && $this->model->loginExpires
        ) {
            return $this->success();
        }

        return $this->failure();
    }

    private function success(): ResponseInterface
    {
        $response = new JsonResponse([
            'data' => [
                'success' => true
            ]
        ]);

        // Set the login cookies.
        $loginExpires = $this->model->loginExpires;
        $expires = $loginExpires->format(DateTimeInterface::COOKIE);

        $setCookie = SetCookie::create(CookieNames::LOGIN_SELECTOR);
        $setCookie = $setCookie->withValue($this->model->loginSelector);
        $setCookie = $setCookie->withPath('/');
        $setCookie = $setCookie->withExpires($expires);
        $setCookie = $setCookie->withSecure(
            $this->environment === Environments::PRODUCTION
        );
        $setCookie = $setCookie->withHttpOnly();
        $response = FigResponseCookies::set($response, $setCookie);

        $setCookie = SetCookie::create(CookieNames::LOGIN_TOKEN);
        $setCookie = $setCookie->withValue($this->model->loginToken);
        $setCookie = $setCookie->withPath('/');
        $setCookie = $setCookie->withExpires($expires);
        $setCookie = $setCookie->withSecure(
            $this->environment === Environments::PRODUCTION
        );
        $setCookie = $setCookie->withHttpOnly();
        return FigResponseCookies::set($response, $setCookie);
    }

    private function failure(): ResponseInterface
    {
        $errorMessage = $this->model->errorMessage;

        $response = new JsonResponse([
            'error' => [
                'message' => $errorMessage
            ]
        ]);

        // Expire any existing login cookies.
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
