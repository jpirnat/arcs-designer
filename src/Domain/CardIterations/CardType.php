<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardIterations;

use function in_array;

// Note: This is a non-readonly class, instead of a readonly class or an enum,
// because it has virtual properties.

final class CardType
{
    public const string AREA = 'area';
    public const string CREATURE = 'creature';
    public const string ITEM = 'item';
    public const string SPELL = 'spell';

    private(set) readonly string $value;

    /**
     * @throws InvalidCardTypeException if $value is invalid.
     */
    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::AREA,
            self::CREATURE,
            self::ITEM,
            self::SPELL,
        ], true)) {
            throw new InvalidCardTypeException("Invalid card type: $value.");
        }

        $this->value = $value;
    }

    public string $name {
        get => match ($this->value) {
            self::AREA => 'Area',
            self::CREATURE => 'Creature',
            self::ITEM => 'Item',
            self::SPELL => 'Spell',
        };
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return [
            new self(self::AREA),
            new self(self::CREATURE),
            new self(self::ITEM),
            new self(self::SPELL),
        ];
    }
}
