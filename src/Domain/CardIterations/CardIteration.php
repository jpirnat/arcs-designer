<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardIterations;

use Jp\ArcsDesigner\Domain\Affinities\AffinityId;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use function mb_strlen;
use function mb_trim;

final class CardIteration
{
    private(set) readonly CardIterationId $id;
    private(set) readonly CardId $cardId;

    public string $name {
        set {
            $value = mb_trim($value);

            if ($value === '') {
                throw new InvalidNameException('Card name cannot be blank.');
            }
            if (mb_strlen($value) > $max = self::MAX_LENGTH_NAME) {
                throw new InvalidNameException("Card name must be $max or fewer characters.");
            }

            $this->name = $value;
        }
    }

    public ?AffinityId $affinityId;

    public ?int $cost {
        set {
            if ($value !== null && $value < 0) {
                throw new InvalidCostException('Card cost cannot be negative.');
            }

            $this->cost = $value;
        }
    }

    public ?bool $enflowable;
    public ?SpeedModifier $speedModifier;
    public ?ZoneModifier $zoneModifier;

    public ?int $startingLife {
        set {
            if ($value !== null && $value <= 0) {
                throw new InvalidStartingLifeException('Starting life must be greater than 0.');
            }

            $this->startingLife = $value;
        }
    }

    public ?int $burden {
        set {
            if ($value !== null && $value < 0) {
                throw new InvalidBurdenException('Burden cannot be negative.');
            }

            $this->burden = $value;
        }
    }

    public ?CardType $cardType;

    public string $rulesText {
        set {
            $value = mb_trim($value);

            if (mb_strlen($value) > $max = self::MAX_LENGTH_RULES_TEXT) {
                throw new InvalidRulesTextException("Card rules text must be $max or fewer characters.");
            }

            $this->rulesText = $value;
        }
    }

    public ?int $attack;
    public ?int $defense;

    private const int MAX_LENGTH_NAME = 32;
    private const int MAX_LENGTH_RULES_TEXT = 1024;

    public function __construct(
        CardIterationId $id,
        CardId $cardId,
        string $name,
        ?AffinityId $affinityId,
        ?int $cost,
        ?bool $enflowable,
        ?SpeedModifier $speedModifier,
        ?ZoneModifier $zoneModifier,
        ?int $startingLife,
        ?int $burden,
        ?CardType $cardType,
        string $rulesText,
        ?int $attack,
        ?int $defense,
    ) {
        $this->id = $id;
        $this->cardId = $cardId;
        $this->name = $name;
        $this->affinityId = $affinityId;
        $this->cost = $cost;
        $this->enflowable = $enflowable;
        $this->speedModifier = $speedModifier;
        $this->zoneModifier = $zoneModifier;
        $this->startingLife = $startingLife;
        $this->burden = $burden;
        $this->cardType = $cardType;
        $this->rulesText = $rulesText;
        $this->attack = $attack;
        $this->defense = $defense;
    }

    public function equals(self $other): bool
    {
        return $this->name === $other->name
            && $this->affinityId?->value === $other->affinityId?->value
            && $this->cost === $other->cost
            && $this->enflowable === $other->enflowable
            && $this->speedModifier?->value === $other->speedModifier?->value
            && $this->zoneModifier?->value === $other->zoneModifier?->value
            && $this->startingLife === $other->startingLife
            && $this->burden === $other->burden
            && $this->cardType?->value === $other->cardType?->value
            && $this->rulesText === $other->rulesText
            && $this->attack === $other->attack
            && $this->defense === $other->defense
        ;
    }
}
