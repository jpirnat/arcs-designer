<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Users;

interface PasswordHashRepositoryInterface
{
    /**
     * @throws UserNotFoundException if no user exists with this email address.
     */
    public function getByEmailAddress(string $emailAddress): string;

    public function save(UserId $userId, string $passwordHash): void;
}
