<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginToken;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenId;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenNotFoundException;
use Jp\ArcsDesigner\Domain\LoginTokens\LoginTokenRepositoryInterface;
use Jp\ArcsDesigner\Domain\Users\UserId;
use PDO;

final readonly class DatabaseLoginTokenRepository implements LoginTokenRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getBySelector(string $selector): LoginToken
    {
        $now = new DateTimeImmutable();

        $stmt = $this->db->prepare(
            'SELECT
                `id`,
                `user_id`,
                `token_hash`,
                `expires`
            FROM `login_tokens`
            WHERE `selector` = :selector
                AND `expires` > :now
            LIMIT 1'
        );
        $stmt->bindValue(':selector', $selector);
        $stmt->bindValue(':now', $now->format('Y-m-d H:i:s'));
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new LoginTokenNotFoundException("No login token exists with selector $selector.");
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return new LoginToken(
            new LoginTokenId($result['id']),
            new UserId($result['user_id']),
            $selector,
            '',
            $result['token_hash'],
            new DateTimeImmutable($result['expires']),
        );
    }

    public function save(LoginToken $loginToken): void
    {
        if ($loginToken->id->isNew) {
            $this->create($loginToken);
        } else {
            $this->update($loginToken);
        }
    }

    private function create(LoginToken $loginToken): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `login_tokens` (
                `user_id`,
                `selector`,
                `token_hash`,
                `expires`
            ) VALUES (
                :user_id,
                :selector,
                :token_hash,
                :expires
            )'
        );
        $stmt->bindValue(':user_id', $loginToken->userId->value, PDO::PARAM_INT);
        $stmt->bindValue(':selector', $loginToken->selector);
        $stmt->bindValue(':token_hash', $loginToken->tokenHash);
        $stmt->bindValue(':expires', $loginToken->expires->format('Y-m-d H:i:s'));
        $stmt->execute();

        /** @noinspection PhpUnhandledExceptionInspection */
        $loginToken->id->set(
            (int) $this->db->lastInsertId()
        );
    }

    private function update(LoginToken $loginToken): void
    {
        $stmt = $this->db->prepare(
            'UPDATE `login_tokens` SET
                `expires` = :expires
            WHERE `id` = :token_id'
        );
        $stmt->bindValue(':expires', $loginToken->expires->format('Y-m-d H:i:s'));
        $stmt->bindValue(':token_id', $loginToken->id->value, PDO::PARAM_INT);
        $stmt->execute();
    }
}
