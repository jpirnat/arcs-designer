<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardComments;

use Jp\ArcsDesigner\Domain\Cards\CardId;

interface CardCommentRepositoryInterface
{
    /**
     * @return CardComment[] Indexed by id. Ordered by created at.
     */
    public function getByCard(CardId $cardId): array;

    public function save(CardComment $comment): void;

    public function delete(CardCommentId $commentId): void;
}
