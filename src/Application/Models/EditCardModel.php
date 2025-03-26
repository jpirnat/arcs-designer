<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Affinities\AffinityId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationNotFoundException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidCardTypeException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidSpeedModifierException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidZoneModifierException;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;
use Jp\ArcsDesigner\Domain\Cards\Card;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Cards\CardRepositoryInterface;

final class EditCardModel
{
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly CardIterationRepositoryInterface $iterationRepository,
        private readonly CardRepositoryInterface $cardRepository,
    ) {}

    /** @noinspection DuplicatedCode */
    public function addCard(
        string $name,
        string $affinityId,
        string $cost,
        string $enflowable,
        string $speedModifier,
        string $zoneModifier,
        string $startingLife,
        string $burden,
        string $cardType,
        string $rulesText,
        string $attack,
        string $defense,
    ): void {
        $this->errorMessage = '';

        try {
            $affinityId = $affinityId !== ''
                ? new AffinityId((int) $affinityId)
                : null;
            $cost = (int) $cost;
            $enflowable = (bool) $enflowable;
            $speedModifier = new SpeedModifier($speedModifier);
            $zoneModifier = new ZoneModifier($zoneModifier);
            $startingLife = (int) $startingLife;
            $burden = (int) $burden;
            $cardType = new CardType($cardType);
            $attack = (int) $attack;
            $defense = (int) $defense;
        } catch (InvalidSpeedModifierException|InvalidZoneModifierException|InvalidCardTypeException $e) {
            $this->errorMessage = $e->getMessage();
        }

        $card = new Card(
            new CardId(),
            $name,
        );
        $this->cardRepository->save($card);

        $iteration = new CardIteration(
            new CardIterationId(),
            $card->id,
            $name,
            $affinityId,
            $cost,
            $enflowable,
            $speedModifier,
            $zoneModifier,
            $startingLife,
            $burden,
            $cardType,
            $rulesText,
            $attack,
            $defense,
        );
        $this->iterationRepository->save($iteration);
    }

    /** @noinspection DuplicatedCode */
    public function editCard(
        string $iterationId,
        string $name,
        string $affinityId,
        string $cost,
        string $enflowable,
        string $speedModifier,
        string $zoneModifier,
        string $startingLife,
        string $burden,
        string $cardType,
        string $rulesText,
        string $attack,
        string $defense,
    ): void {
        $this->errorMessage = '';

        try {
            $iterationId = new CardIterationId((int) $iterationId);
            $affinityId = $affinityId !== ''
                ? new AffinityId((int) $affinityId)
                : null;
            $cost = (int) $cost;
            $enflowable = (bool) $enflowable;
            $speedModifier = new SpeedModifier($speedModifier);
            $zoneModifier = new ZoneModifier($zoneModifier);
            $startingLife = (int) $startingLife;
            $burden = (int) $burden;
            $cardType = new CardType($cardType);
            $attack = (int) $attack;
            $defense = (int) $defense;
        } catch (InvalidSpeedModifierException|InvalidZoneModifierException|InvalidCardTypeException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        try {
            $old = $this->iterationRepository->getById($iterationId);
        } catch (CardIterationNotFoundException) {
            return;
        }

        $new = new CardIteration(
            new CardIterationId(),
            $old->cardId,
            $name,
            $affinityId,
            $cost,
            $enflowable,
            $speedModifier,
            $zoneModifier,
            $startingLife,
            $burden,
            $cardType,
            $rulesText,
            $attack,
            $defense,
        );

        if ($new->equals($old)) {
            // Nothing changed.
            return;
        }

        $this->iterationRepository->save($new);

        // Update the card's name.
        /** @noinspection PhpUnhandledExceptionInspection */
        $card = $this->cardRepository->getById($new->cardId);
        $card->name = $new->name;
        $this->cardRepository->save($card);
    }
}
