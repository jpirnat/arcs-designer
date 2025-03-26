<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\CardComments\CardComment;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentId;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentRepositoryInterface;
use PDO;

final readonly class DatabaseCardCommentRepository implements CardCommentRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    public function save(CardComment $comment): void
    {
        if ($comment->id->isNew) {
            $this->create($comment);
        }
    }

    private function create(CardComment $comment): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `card_comments` (
                `card_id`,
                `iteration_id`,
                `user_id`,
                `text`
            ) VALUES (
                :card_id,
                :iteration_id,
                :user_id,
                :text
            )'
        );
        $stmt->bindValue(':card_id', $comment->cardId->value, PDO::PARAM_INT);
        $stmt->bindValue(':iteration_id', $comment->iterationId->value, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $comment->userId->value, PDO::PARAM_INT);
        $stmt->bindValue(':text', $comment->text);
        $stmt->execute();

        /** @noinspection PhpUnhandledExceptionInspection */
        $comment->id->set(
            (int) $this->db->lastInsertId()
        );
    }

    public function delete(CardCommentId $commentId): void
    {
        $stmt = $this->db->prepare(
            'DELETE
            FROM `card_comments`
            WHERE `id` = :comment_id'
        );
        $stmt->bindValue(':comment_id', $commentId->value, PDO::PARAM_INT);
        $stmt->execute();
    }
}
