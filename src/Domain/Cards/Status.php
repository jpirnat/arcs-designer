<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Cards;

use function in_array;

// Note: This is a non-readonly class, instead of a readonly class or an enum,
// because it has virtual properties.

final class Status
{
    public const string IN_PROGRESS = 'in-progress';
    public const string FINAL = 'final';

    private(set) readonly string $value;

    /**
     * @throws InvalidStatusException if $value is invalid.
     */
    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::IN_PROGRESS,
            self::FINAL,
        ], true)) {
            throw new InvalidStatusException("Invalid status: $value.");
        }

        $this->value = $value;
    }

    public string $name {
        get => match ($this->value) {
            self::IN_PROGRESS => 'In Design',
            self::FINAL => 'Final',
        };
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return [
            new self(self::IN_PROGRESS),
            new self(self::FINAL),
        ];
    }
}
