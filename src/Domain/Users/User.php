<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Users;

final readonly class User
{
    public function __construct(
        private(set) UserId $id,
        private(set) string $emailAddress,
        private(set) string $displayName,
    ) {}
}
