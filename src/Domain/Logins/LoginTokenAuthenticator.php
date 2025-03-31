<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Logins;

use Jp\ArcsDesigner\Domain\LoginTokens\LoginToken;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenNotFoundException;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenRepositoryInterface;
use function bin2hex;
use function hash;
use function hash_equals;
use function random_bytes;

final readonly class LoginTokenAuthenticator
{
    /**
     * A randomly generated token hash that is validated against when the user
     * gives an invalid login selector, to protect against timing attacks.
     */
    private string $dummyHash;

    public function __construct(
        private LoginTokenRepositoryInterface $loginTokenRepository,
    ) {
        /** @noinspection PhpUnhandledExceptionInspection */
        $dummyToken = bin2hex(random_bytes(16)); // length 32
        $this->dummyHash = hash('sha256', $dummyToken); // length 64
    }

    /**
     * Authenticate a user via login token.
     *
     * @throws InvalidLoginSelectorException if $selector is invalid.
     * @throws IncorrectLoginTokenException if $token is invalid.
     */
    public function authenticate(string $selector, string $token): LoginToken
    {
        // Get the login token for this selector.
        try {
            $loginToken = $this->loginTokenRepository->getBySelector($selector);
        } catch (LoginTokenNotFoundException) {
            // No login token exists with this selector. But to prevent that
            // information from leaking to a timing attack, do a token
            // validation anyway.
            /** @noinspection PhpExpressionResultUnusedInspection */
            hash_equals($this->dummyHash, $token);

            throw new InvalidLoginSelectorException(
                "Invalid login selector: $selector."
            );
        }

        if (!hash_equals($loginToken->tokenHash, hash('sha256', $token))) {
            throw new IncorrectLoginTokenException('Login token does not match.');
        }

        return $loginToken;
    }
}
