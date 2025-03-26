<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardIterations;

use function in_array;

// Note: This is a non-readonly class, instead of a readonly class or an enum,
// because it has virtual properties.

final class SpeedModifier
{
    public const string SLOW = 'slow';
    public const string FAST = 'fast';

    private(set) readonly string $value;

    /**
     * @throws InvalidSpeedModifierException if $value is invalid.
     */
    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::SLOW,
            self::FAST,
        ], true)) {
            throw new InvalidSpeedModifierException("Invalid speed modifier: $value.");
        }

        $this->value = $value;
    }

    public string $name {
        get => match ($this->value) {
            self::SLOW => '',
            self::FAST => 'Fast',
        };
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return [
            new self(self::SLOW),
            new self(self::FAST),
        ];
    }
}
