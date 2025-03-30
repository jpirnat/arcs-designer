<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\CookieNames;
use Jp\ArcsDesigner\Application\Models\LogoutModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class LogoutController
{
    public function __construct(
        private LogoutModel $model,
    ) {}

    public function setData(ServerRequestInterface $request): void
    {
        $selector = $request->getCookieParams()[CookieNames::LOGIN_SELECTOR] ?? '';
        $token = $request->getCookieParams()[CookieNames::LOGIN_TOKEN] ?? '';

        $this->model->setData($selector, $token);
    }
}
