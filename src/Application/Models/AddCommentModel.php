<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\CardComments\CardComment;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentId;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardComments\InvalidTextException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationNotFoundException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\Users\UserId;
use Jp\ArcsDesigner\Domain\Users\UserRepositoryInterface;

final class AddCommentModel
{
    private(set) array $comment = [];
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly CardIterationRepositoryInterface $iterationRepository,
        private readonly CardCommentRepositoryInterface $commentRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function setData(
        UserId $userId,
        string $iterationId,
        string $commentText,
    ): void {
        $this->comment = [];
        $this->errorMessage = '';

        $iterationId = new CardIterationId((int) $iterationId);

        try {
            $iteration = $this->iterationRepository->getById($iterationId);
        } catch (CardIterationNotFoundException) {
            return;
        }

        try {
            $comment = new CardComment(
                new CardCommentId(),
                $iteration->cardId,
                $iteration->id,
                $commentText,
                $userId,
                new DateTimeImmutable(),
            );
        } catch (InvalidTextException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->commentRepository->save($comment);

        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->userRepository->getById($comment->createdBy);

        $this->comment = [
            'id' => $comment->id->value,
            'iterationId' => $comment->iterationId->value,
            'text' => $comment->text,
            'createdBy' => $user->displayName,
            'createdAt' => $comment->createdAt->format('M j, Y g:ia'),
        ];
    }
}
