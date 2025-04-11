<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Sets;

use function mb_strlen;
use function mb_trim;

final class Set
{
    private(set) readonly SetId $id;

    public string $name {
        set {
            $value = mb_trim($value);

            if ($value === '') {
                throw new InvalidNameException('Set name cannot be blank.');
            }
            if (mb_strlen($value) > $max = self::MAX_LENGTH_NAME) {
                throw new InvalidNameException("Set name must be $max or fewer characters.");
            }

            $this->name = $value;
        }
    }

    public const int MAX_LENGTH_NAME = 32;

    /**
     * @throws InvalidNameException if $name is invalid.
     * @noinspection PhpDocRedundantThrowsInspection
     */
    public function __construct(
        SetId $id,
        string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
    }
}
