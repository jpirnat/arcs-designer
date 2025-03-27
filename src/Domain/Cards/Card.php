<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Cards;

use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidNameException;
use function mb_strlen;
use function mb_trim;

final class Card
{
    private(set) readonly CardId $id;

    public string $name {
        set {
            $value = mb_trim($value);

            if ($value === '') {
                throw new InvalidNameException('Card name cannot be blank.');
            }
            if (mb_strlen($value) > $max = CardIteration::MAX_LENGTH_NAME) {
                throw new InvalidNameException("Card name must be $max or fewer characters.");
            }

            $this->name = $value;
        }
    }

    /**
     * @throws InvalidNameException if $name is invalid.
     */
    public function __construct(
        CardId $id,
        string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
    }
}
