<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Affinities\AffinityRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardComments\CardComment;
use Jp\ArcsDesigner\Domain\CardComments\CardCommentRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\SetCards\SetCardRepositoryInterface;
use Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface;
use Jp\ArcsDesigner\Domain\Users\UserRepositoryInterface;
use function nl2br;

final class CardModel
{
    private(set) array $sets = [];
    private(set) array $setIds = [];
    private(set) array $iterations = [];
    private(set) array $current = [];
    private(set) ?array $comparing = null;
    private(set) array $comments = [];
    private(set) array $affinities = [];
    private(set) array $speedModifiers = [];
    private(set) array $zoneModifiers = [];
    private(set) array $cardTypes = [];
    private(set) array $maxLengths = [];

    public function __construct(
        private readonly SetRepositoryInterface $setRepository,
        private readonly SetCardRepositoryInterface $setCardRepository,
        private readonly CardIterationRepositoryInterface $iterationRepository,
        private readonly CardCommentRepositoryInterface $commentRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AffinityRepositoryInterface $affinityRepository,
    ) {}

    public function setAddData(string $setId): void
    {
        $this->sets = [];
        $this->setIds = $setId !== ''
            ? [(int) $setId]
            : [];
        $this->iterations = [];
        $this->comparing = null;
        $this->comments = [];
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [];

        $this->current = [
            'iterationId' => null,
            'cardId' => null,
            'name' => '',
            'affinityId' => null,
            'cost' => null,
            'enflowable' => null,
            'speedModifier' => null,
            'zoneModifier' => null,
            'startingLife' => null,
            'burden' => null,
            'cardType' => null,
            'rulesText' => '',
            'power' => null,
            'health' => null,
        ];

        $this->setCommonData();
    }

    public function setEditData(string $cardId): void
    {
        $this->sets = [];
        $this->setIds = [];
        $this->iterations = [];
        $this->current = [];
        $this->comparing = null;
        $this->comments = [];
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [];

        $cardId = new CardId((int) $cardId);

        $setCards = $this->setCardRepository->getByCard($cardId);
        foreach ($setCards as $setCard) {
            $this->setIds[] = $setCard->setId->value;
        }

        $iteration = $this->iterationRepository->getCurrent($cardId);
        $this->current = $this->getIterationData($iteration);
        $this->comparing = $this->current;

        $iterations = $this->iterationRepository->getByCard($cardId);
        foreach ($iterations as $iteration) {
            $this->iterations[] = $this->getIterationData($iteration);
        }

        $comments = $this->commentRepository->getByCard($cardId);
        foreach ($comments as $comment) {
            $this->comments[] = $this->getCommentData($comment);
        }

        $this->setCommonData();
    }

    private function getIterationData(CardIteration $iteration): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->userRepository->getById($iteration->createdBy);

        return [
            'iterationId' => $iteration->id->value,
            'cardId' => $iteration->cardId->value,
            'name' => $iteration->name,
            'affinityId' => $iteration->affinityId?->value,
            'cost' => $iteration->cost,
            'enflowable' => $iteration->enflowable,
            'speedModifier' => $iteration->speedModifier?->value,
            'zoneModifier' => $iteration->zoneModifier?->value,
            'startingLife' => $iteration->startingLife,
            'burden' => $iteration->burden,
            'cardType' => $iteration->cardType?->value,
            'rulesText' => $iteration->rulesText,
            'power' => $iteration->power,
            'health' => $iteration->health,
            'createdBy' => $user->displayName,
            'createdAt' => $iteration->createdAt->format('M j, Y g:ia'),
        ];
    }

    private function getCommentData(CardComment $comment): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->userRepository->getById($comment->createdBy);

        return [
            'id' => $comment->id->value,
            'iterationId' => $comment->iterationId->value,
            'text' => nl2br($comment->text),
            'createdBy' => $user->displayName,
            'createdAt' => $comment->createdAt->format('M j, Y g:ia'),
        ];
    }

    private function setCommonData(): void
    {
        $this->sets = [];
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [
            'name' => CardIteration::MAX_LENGTH_NAME,
            'rulesText' => CardIteration::MAX_LENGTH_RULES_TEXT,
        ];

        $sets = $this->setRepository->getAll();
        foreach ($sets as $set) {
            $this->sets[] = [
                'id' => $set->id->value,
                'name' => $set->name,
            ];
        }

        $affinities = $this->affinityRepository->getAll();
        foreach ($affinities as $affinity) {
            $this->affinities[] = [
                'id' => $affinity->id->value,
                'name' => $affinity->name,
            ];
        }

        $speedModifiers = SpeedModifier::getAll();
        foreach ($speedModifiers as $speedModifier) {
            $this->speedModifiers[] = [
                'value' => $speedModifier->value,
                'name' => $speedModifier->name,
            ];
        }

        $zoneModifiers = ZoneModifier::getAll();
        foreach ($zoneModifiers as $zoneModifier) {
            $this->zoneModifiers[] = [
                'value' => $zoneModifier->value,
                'name' => $zoneModifier->name,
            ];
        }

        $cardTypes = CardType::getAll();
        foreach ($cardTypes as $cardType) {
            $this->cardTypes[] = [
                'value' => $cardType->value,
                'name' => $cardType->name,
            ];
        }
    }
}
