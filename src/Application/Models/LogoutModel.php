<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\Logins\IncorrectLoginTokenException;
use Jp\ArcsDesigner\Domain\Logins\InvalidLoginSelectorException;
use Jp\ArcsDesigner\Domain\Logins\LoginTokenAuthenticator;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class LogoutModel
{
    public function __construct(
        private SessionInterface $session,
        private LoginTokenAuthenticator $loginTokenAuthenticator,
        private LoginTokenRepositoryInterface $loginTokenRepository,
    ) {}

    public function setData(string $selector, string $token): void
    {
        $this->session->invalidate();

        if (!$selector || !$token) {
            return;
        }

        try {
            $loginToken = $this->loginTokenAuthenticator->authenticate(
                $selector,
                $token,
            );
        } catch (InvalidLoginSelectorException | IncorrectLoginTokenException) {
            return;
        }

        $loginToken->expires = new DateTimeImmutable();
        $this->loginTokenRepository->save($loginToken);
    }
}
