<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardIterations;

use function in_array;

// Note: This is a non-readonly class, instead of a readonly class or an enum,
// because it has virtual properties.

final class ZoneModifier
{
    public const string HAND = 'hand';
    public const string LEADER = 'leader';

    private(set) readonly string $value;

    /**
     * @throws InvalidZoneModifierException if $value is invalid.
     */
    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::HAND,
            self::LEADER,
        ], true)) {
            throw new InvalidZoneModifierException("Invalid zone modifier: $value.");
        }

        $this->value = $value;
    }

    public string $name {
        get => match ($this->value) {
            self::HAND => '',
            self::LEADER => 'Leader',
        };
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return [
            new self(self::HAND),
            new self(self::LEADER),
        ];
    }
}
