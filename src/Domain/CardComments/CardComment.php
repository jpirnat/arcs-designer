<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardComments;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Users\UserId;
use function mb_strlen;
use function mb_trim;

final class CardComment
{
    private(set) readonly CardCommentId $id;
    private(set) readonly CardId $cardId;
    private(set) readonly CardIterationId $iterationId;

    public string $text {
        set {
            $value = mb_trim($value);

            if ($value === '') {
                throw new InvalidTextException('Comment cannot be blank.');
            }
            if (mb_strlen($value) > $max = self::MAX_LENGTH_TEXT) {
                throw new InvalidTextException("Comment must be $max or fewer characters.");
            }

            $this->text = $value;
        }
    }

    private(set) readonly UserId $createdBy;
    private(set) DateTimeImmutable $createdAt;

    private const int MAX_LENGTH_TEXT = 1024;

    /**
     * @throws InvalidTextException if $text is invalid.
     */
    public function __construct(
        CardCommentId $id,
        CardId $cardId,
        CardIterationId $iterationId,
        string $text,
        UserId $createdBy,
        DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->cardId = $cardId;
        $this->iterationId = $iterationId;
        $this->text = $text;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }
}
