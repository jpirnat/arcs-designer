<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\LoginTokens;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\Users\UserId;
use function bin2hex;
use function hash;
use function random_bytes;

final readonly class LoginTokenGenerator
{
    public function __construct(
        private LoginTokenRepositoryInterface $loginTokenRepository,
    ) {}

    /**
     * Generate a new login token for this user.
     */
    public function generateFor(UserId $userId): LoginToken
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $selector = bin2hex(random_bytes(8)); // length 16
        /** @noinspection PhpUnhandledExceptionInspection */
        $token = bin2hex(random_bytes(16)); // length 32
        $tokenHash = hash('sha256', $token); // length 64

        $loginToken = new LoginToken(
            new LoginTokenId(),
            $userId,
            $selector,
            $token,
            $tokenHash,
            new DateTimeImmutable('+1 week'),
        );

        $this->loginTokenRepository->save($loginToken);

        return $loginToken;
    }
}
