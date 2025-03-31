<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\CardComments\CardComment;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentId;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Users\UserId;
use PDO;

final readonly class DatabaseCardCommentRepository implements CardCommentRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getByCard(CardId $cardId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                `id`,
                `iteration_id`,
                `text`,
                `created_by`,
                `created_at`
            FROM `card_comments`
            WHERE `card_id` = :card_id
            ORDER BY `created_at` DESC'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();

        $comments = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $comment = new CardComment(
                new CardCommentId($result['id']),
                $cardId,
                new CardIterationId($result['iteration_id']),
                $result['text'],
                new UserId($result['created_by']),
                new DateTimeImmutable($result['created_at']),
            );

            $comments[$result['id']] = $comment;
        }

        return $comments;
    }

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
                `text`,
                `created_by`
            ) VALUES (
                :card_id,
                :iteration_id,
                :text,
                :created_by
            )'
        );
        $stmt->bindValue(':card_id', $comment->cardId->value, PDO::PARAM_INT);
        $stmt->bindValue(':iteration_id', $comment->iterationId->value, PDO::PARAM_INT);
        $stmt->bindValue(':text', $comment->text);
        $stmt->bindValue(':created_by', $comment->createdBy->value, PDO::PARAM_INT);
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
