<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\LoginTokens;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\Users\UserId;

final class LoginToken
{
    public function __construct(
        private(set) readonly LoginTokenId $id,
        private(set) readonly UserId $userId,
        private(set) readonly string $selector,
        private(set) readonly string $token,
        private(set) readonly string $tokenHash,
        public DateTimeImmutable $expires,
    ) {}
}
