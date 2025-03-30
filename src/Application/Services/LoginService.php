<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Services;

use DateTimeImmutable;
use Jp\ArcsDesigner\Application\SessionVariables;
use Jp\ArcsDesigner\Domain\Logins\IncorrectLoginTokenException;
use Jp\ArcsDesigner\Domain\Logins\IncorrectPasswordException;
use Jp\ArcsDesigner\Domain\Logins\InvalidEmailAddressException;
use Jp\ArcsDesigner\Domain\Logins\InvalidLoginSelectorException;
use Jp\ArcsDesigner\Domain\Logins\LoginPasswordAuthenticator;
use Jp\ArcsDesigner\Domain\Logins\LoginTokenAuthenticator;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginToken;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenGenerator;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class LoginService
{
    public function __construct(
        private LoginPasswordAuthenticator $loginPasswordAuthenticator,
        private SessionInterface $session,
        private LoginTokenGenerator $loginTokenGenerator,
        private LoginTokenAuthenticator $loginTokenAuthenticator,
        private LoginTokenRepositoryInterface $loginTokenRepository,
    ) {}

    /**
     * Login a user via their email address and password.
     *
     * @throws InvalidEmailAddressException if $emailAddress is invalid.
     * @throws IncorrectPasswordException if $password is invalid.
     */
    public function loginViaPassword(
        string $emailAddress,
        string $password,
    ): LoginToken {
        $userId = $this->loginPasswordAuthenticator->authenticate(
            $emailAddress,
            $password,
        );

        // Log the user in.
        $this->session->set(SessionVariables::USER_ID, $userId->value);

        // Create a login token for the user.
        return $this->loginTokenGenerator->generateFor($userId);
    }

    /**
     * Login a user via their login token.
     *
     * @throws InvalidLoginSelectorException if $selector is invalid.
     * @throws IncorrectLoginTokenException if $token is invalid.
     */
    public function loginViaToken(
        string $selector,
        string $token,
    ): LoginToken {
        $loginToken = $this->loginTokenAuthenticator->authenticate(
            $selector,
            $token,
        );

        // Log the user in.
        $this->session->set(SessionVariables::USER_ID, $loginToken->userId->value);

        // Extend the login token's expiration date.
        $loginToken->expires = new DateTimeImmutable('+1 week');
        $this->loginTokenRepository->save($loginToken);

        return $loginToken;
    }
}
