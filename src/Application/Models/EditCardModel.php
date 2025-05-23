<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\Affinities\AffinityId;
use Jp\ArcsDesigner\Domain\CardComments\CardComment;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentId;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardComments\InvalidTextException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationNotFoundException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidCardTypeException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidCostException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidNameException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidRulesTextException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidSpeedModifierException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidStartingLifeException;
use Jp\ArcsDesigner\Domain\CardIterations\InvalidZoneModifierException;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;
use Jp\ArcsDesigner\Domain\Cards\Card;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Cards\CardRepositoryInterface;
use Jp\ArcsDesigner\Domain\Cards\Status;
use Jp\ArcsDesigner\Domain\SetCards\SetCard;
use Jp\ArcsDesigner\Domain\SetCards\SetCardRepositoryInterface;
use Jp\ArcsDesigner\Domain\Sets\SetId;
use Jp\ArcsDesigner\Domain\Users\UserId;

final class EditCardModel
{
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly CardRepositoryInterface $cardRepository,
        private readonly SetCardRepositoryInterface $setCardRepository,
        private readonly CardIterationRepositoryInterface $iterationRepository,
        private readonly CardCommentRepositoryInterface $commentRepository,
    ) {}

    /** @noinspection DuplicatedCode */
    public function addCard(
        UserId $userId,
        array $setIds,
        string $name,
        string $affinityId,
        string $cost,
        string $enflowable,
        string $speedModifier,
        string $zoneModifier,
        string $startingLife,
        string $cardType,
        string $rulesText,
        string $power,
        string $health,
        string $commentText,
    ): void {
        $this->errorMessage = '';

        $affinityId = $affinityId !== ''
            ? new AffinityId((int) $affinityId)
            : null;
        $cost = $cost !== ''
            ? (int) $cost
            : null;
        $enflowable = $enflowable !== ''
            ? (bool) $enflowable
            : null;
        try {
            $speedModifier = $speedModifier !== ''
                ? new SpeedModifier($speedModifier)
                : null;
        } catch (InvalidSpeedModifierException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
        try {
            $zoneModifier = $zoneModifier !== ''
                ? new ZoneModifier($zoneModifier)
                : null;
        } catch (InvalidZoneModifierException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
        $startingLife = $startingLife !== ''
            ? (int) $startingLife
            : null;
        try {
            $cardType = $cardType !== ''
                ? new CardType($cardType)
                : null;
        } catch (InvalidCardTypeException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
        $power = $power !== ''
            ? (int) $power
            : null;
        $health = $health !== ''
            ? (int) $health
            : null;

        $cardId = new CardId();

        $card = new Card(
            $cardId,
            new Status(Status::IN_PROGRESS),
        );

        try {
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
                $cardType,
                $rulesText,
                $power,
                $health,
                $userId,
                new DateTimeImmutable(),
            );
        } catch (
            InvalidNameException
            | InvalidCostException
            | InvalidStartingLifeException
            | InvalidRulesTextException $e
        ) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->cardRepository->save($card);
        $this->saveSets($card->id, $setIds);
        $this->iterationRepository->save($iteration);
        $this->saveComment($iteration, $commentText, $userId);
    }

    /** @noinspection DuplicatedCode */
    public function editCard(
        UserId $userId,
        array $setIds,
        string $iterationId,
        string $name,
        string $affinityId,
        string $cost,
        string $enflowable,
        string $speedModifier,
        string $zoneModifier,
        string $startingLife,
        string $cardType,
        string $rulesText,
        string $power,
        string $health,
        string $commentText,
    ): void {
        $this->errorMessage = '';

        $iterationId = new CardIterationId((int) $iterationId);
        $affinityId = $affinityId !== ''
            ? new AffinityId((int) $affinityId)
            : null;
        $cost = $cost !== ''
            ? (int) $cost
            : null;
        $enflowable = $enflowable !== ''
            ? (bool) $enflowable
            : null;
        try {
            $speedModifier = $speedModifier !== ''
                ? new SpeedModifier($speedModifier)
                : null;
        } catch (InvalidSpeedModifierException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
        try {
            $zoneModifier = $zoneModifier !== ''
                ? new ZoneModifier($zoneModifier)
                : null;
        } catch (InvalidZoneModifierException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
        $startingLife = $startingLife !== ''
            ? (int) $startingLife
            : null;
        try {
            $cardType = $cardType !== ''
                ? new CardType($cardType)
                : null;
        } catch (InvalidCardTypeException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
        $power = $power !== ''
            ? (int) $power
            : null;
        $health = $health !== ''
            ? (int) $health
            : null;

        try {
            $old = $this->iterationRepository->getById($iterationId);
        } catch (CardIterationNotFoundException) {
            return;
        }

        try {
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
                $cardType,
                $rulesText,
                $power,
                $health,
                $userId,
                new DateTimeImmutable(),
            );
        } catch (
            InvalidNameException
            | InvalidCostException
            | InvalidStartingLifeException
            | InvalidRulesTextException $e
        ) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->saveSets($old->cardId, $setIds);

        if ($new->changedFrom($old)) {
            $this->iterationRepository->save($new);
            $this->saveComment($new, $commentText, $userId);
        } else {
            $this->saveComment($old, $commentText, $userId);
        }
    }

    private function saveSets(CardId $cardId, array $setIds): void
    {
        $this->setCardRepository->deleteByCard($cardId);

        foreach ($setIds as $setId) {
            $setId = new SetId((int) $setId);
            $this->setCardRepository->save(new SetCard($setId, $cardId));
        }
    }

    private function saveComment(
        CardIteration $iteration,
        string $commentText,
        UserId $userId,
    ): void {
        if (!$commentText) {
            return;
        }

        try {
            $comment = new CardComment(
                new CardCommentId(),
                $iteration->cardId,
                $iteration->id,
                $commentText,
                $userId,
                new DateTimeImmutable(),
            );
        } catch (InvalidTextException) {
            return;
        }

        $this->commentRepository->save($comment);
    }
}
