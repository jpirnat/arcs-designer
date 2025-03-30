<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use DateTimeInterface;
use Jp\ArcsDesigner\Application\Services\LoginService;
use Jp\ArcsDesigner\Domain\Logins\IncorrectPasswordException;
use Jp\ArcsDesigner\Domain\Logins\InvalidEmailAddressException;

final class LoginModel
{
    private(set) string $loginSelector = '';
    private(set) string $loginToken = '';
    private(set) ?DateTimeInterface $loginExpires = null;
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly LoginService $loginService,
    ) {}

    /**
     * Submit the login form.
     */
    public function submit(string $emailAddress, string $password): void
    {
        $this->errorMessage = '';

        try {
            $loginToken = $this->loginService->loginViaPassword(
                $emailAddress,
                $password,
            );
        } catch (InvalidEmailAddressException | IncorrectPasswordException) {
            $this->errorMessage = 'Invalid email address or password.';
            return;
        }

        $this->loginSelector = $loginToken->selector;
        $this->loginToken = $loginToken->token;
        $this->loginExpires = $loginToken->expires;
    }
}
