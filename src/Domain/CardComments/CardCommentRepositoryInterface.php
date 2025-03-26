<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardComments;

interface CardCommentRepositoryInterface
{
    public function save(CardComment $comment): void;

    public function delete(CardCommentId $commentId): void;
}
