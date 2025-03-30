<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\LoginModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class LoginController
{
    public function __construct(
        private LoginModel $model,
    ) {}

    /**
     * Submit the login form.
     */
    public function submit(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $emailAddress = (string) ($data['emailAddress'] ?? '');
        $password = (string) ($data['password'] ?? '');

        $this->model->submit($emailAddress, $password);
    }
}
