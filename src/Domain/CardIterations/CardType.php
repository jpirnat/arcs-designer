<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardIterations;

use function in_array;

// Note: This is a non-readonly class, instead of a readonly class or an enum,
// because it has virtual properties.

final class CardType
{
    public const string CREATURE = 'creature';
    public const string SPELL = 'spell';
    public const string ITEM = 'item';
    public const string AREA = 'area';

    private(set) readonly string $value;

    /**
     * @throws InvalidCardTypeException if $value is invalid.
     */
    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::CREATURE,
            self::SPELL,
            self::ITEM,
            self::AREA,
        ], true)) {
            throw new InvalidCardTypeException("Invalid card type: $value.");
        }

        $this->value = $value;
    }

    public string $name {
        get => match ($this->value) {
            self::CREATURE => 'Creature',
            self::SPELL => 'Spell',
            self::ITEM => 'Item',
            self::AREA => 'Area',
        };
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return [
            new self(self::CREATURE),
            new self(self::SPELL),
            new self(self::ITEM),
            new self(self::AREA),
        ];
    }
}
