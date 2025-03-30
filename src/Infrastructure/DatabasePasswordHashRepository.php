<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Users\PasswordHashRepositoryInterface;
use Jp\ArcsDesigner\Domain\Users\UserId;
use Jp\ArcsDesigner\Domain\Users\UserNotFoundException;
use PDO;

final readonly class DatabasePasswordHashRepository implements PasswordHashRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getByEmailAddress(string $emailAddress): string
    {
        $stmt = $this->db->prepare(
            'SELECT
                `password_hash`
            FROM `users`
            WHERE `email_address` = :email_address
            LIMIT 1'
        );
        $stmt->bindValue(':email_address', $emailAddress);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException("No user exists with email address $emailAddress.");
        }

        return $result['password_hash'];
    }

    public function save(UserId $userId, string $passwordHash): void
    {
        $stmt = $this->db->prepare(
            'UPDATE `users` SET
                `password_hash` = :password_hash
            WHERE `id` = :user_id'
        );
        $stmt->bindValue(':password_hash', $passwordHash);
        $stmt->bindValue(':user_id', $userId->value, PDO::PARAM_INT);
        $stmt->execute();
    }
}
