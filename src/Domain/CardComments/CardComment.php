<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardComments;

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
    private(set) readonly UserId $userId;

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

    private const int MAX_LENGTH_TEXT = 1024;

    public function __construct(
        CardCommentId $id,
        CardId $cardId,
        CardIterationId $iterationId,
        UserId $userId,
        string $text,
    ) {
        $this->id = $id;
        $this->cardId = $cardId;
        $this->iterationId = $iterationId;
        $this->userId = $userId;
        $this->text = $text;
    }
}
