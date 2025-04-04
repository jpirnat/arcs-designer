<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Users\User;
use Jp\ArcsDesigner\Domain\Users\UserId;
use Jp\ArcsDesigner\Domain\Users\UserNotFoundException;
use Jp\ArcsDesigner\Domain\Users\UserRepositoryInterface;
use PDO;

final readonly class DatabaseUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getById(UserId $userId): User
    {
        $stmt = $this->db->prepare(
            'SELECT
                `email_address`,
                `display_name`
            FROM `users`
            WHERE `id` = :user_id
            LIMIT 1'
        );
        $stmt->bindValue('user_id', $userId->value, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException("No user exists with id $userId->value.");
        }

        return new User(
            $userId,
            $result['email_address'],
            $result['display_name'],
        );
    }

    /**
     * @inheritDoc
     */
    public function getByEmailAddress(string $emailAddress): User
    {
        $stmt = $this->db->prepare(
            'SELECT
                `id`,
                `display_name`
            FROM `users`
            WHERE `email_address` = :email_address
            LIMIT 1'
        );
        $stmt->bindValue('email_address', $emailAddress);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException("No user exists with email address $emailAddress.");
        }

        return new User(
            new UserId($result['id']),
            $emailAddress,
            $result['display_name'],
        );
    }
}